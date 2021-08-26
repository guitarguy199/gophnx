<?php

/**
 * Original installer files manager
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2 Full Documentation
 *
 * @package SC\DUPX\U
 *
 */

defined('ABSPATH') || defined('DUPXABSPATH') || exit;

use Duplicator\Installer\Utils\Log\Log;
use Duplicator\Installer\Core\Params\PrmMng;
use Duplicator\Installer\Core\Params\Items\ParamFormTables;

/**
 * Original installer files manager
 * singleton class
 */
final class DUPX_DB_Tables {
    /**
     *
     * @var self
     */
    private static $instance = null;

    /**
     *
     * @var DUPX_DB_Table_item[]
     */
    private $tables = array();

    /**
     *
     * @return self
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $confTables = (array) DUPX_ArchiveConfig::getInstance()->dbInfo->tablesList;
        foreach ($confTables as $tableName => $tableInfo) {
            $rows = ($tableInfo->insertedRows === false ? $tableInfo->inaccurateRows : $tableInfo->insertedRows);

            $this->tables[$tableName] = new DUPX_DB_Table_item($tableName, $rows, $tableInfo->size);
        }

        Log::info('CONSTRUCT TABLES: ' . Log::v2str($this->tables), Log::LV_HARD_DEBUG);
    }

    /**
     *
     * @return DUPX_DB_Table_item[]
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     *
     * @return string[]
     */
    public function getTablesNames()
    {
        return array_keys($this->tables);
    }

    /**
     * get the list of extracted tables names
     *
     * @return string[]
     */
    public function getNewTablesNames()
    {
        $result = array();

        foreach ($this->tables as $tableObj) {
            if ($tableObj->extract()) {
                $result[] = $tableObj->getNewName();
            }
        }

        return $result;
    }

    /**
     *
     * @return string[]
     */
    public function getReplaceTablesNames()
    {
        $result = array();

        foreach ($this->tables as $tableObj) {
            if ($tableObj->replaceEngine()) {
                $result[] = $tableObj->getNewName();
            }
        }

        return $result;
    }

    /**
     *
     * @param id $subsiteId
     * @return string
     */
    public function getSubsiteTablesNewNames($subsiteId)
    {
        $result = array();

        foreach ($this->tables as $tableObj) {
            if ($tableObj->extract() && $tableObj->getSubsisteId() === $subsiteId) {
                $result[] = $tableObj->getNewName();
            }
        }

        return $result;
    }

    /**
     * return list of current standalone site tables without prefix
     *
     * @return string[]
     */
    public function getStandaoneTablesWithoutPrefix()
    {
        $standaloneTables = null;

        if (is_null($standaloneTables)) {
            $standaloneTables = array();
            $standaloneId     = PrmMng::getInstance()->getValue(PrmMng::PARAM_SUBSITE_ID);

            foreach ($this->tables as $tableObj) {
                if ($tableObj->getSubsisteId() === $standaloneId) {
                    $standaloneTables[] = $tableObj->getNameWithoutPrefix();
                }
            }
        }

        return $standaloneTables;
    }

    public function getTablesToSkip()
    {
        $result = array();

        foreach ($this->tables as $tableObj) {
            if (!$tableObj->extract()) {
                $result[] = $tableObj->getOriginalName();
            }
        }

        return $result;
    }

    /**
     *
     * @param type $table
     * @return DUPX_DB_Table_item // false if table don't exists
     *
     * @throws Exception
     */
    public function getTableObjByName($table)
    {
        if (!isset($this->tables[$table])) {
            throw new Exception('TABLE ' . $table . ' Isn\'t in list');
        }

        return $this->tables[$table];
    }

    /**
     *
     * @return array
     */
    public function getRenameTablesMapping()
    {
        $mapping  = array();
        $diffData = array();

        foreach ($this->tables as $tableObj) {
            if (!$tableObj->extract()) {
                // skip stable not extracted
                continue;
            }

            if (!$tableObj->isDiffPrefix($diffData)) {
                continue;
            }

            if (!isset($mapping[$diffData['oldPrefix']])) {
                $mapping[$diffData['oldPrefix']] = array();
            }

            if (!isset($mapping[$diffData['oldPrefix']][$diffData['newPrefix']])) {
                $mapping[$diffData['oldPrefix']][$diffData['newPrefix']] = array();
            }

            $mapping[$diffData['oldPrefix']][$diffData['newPrefix']][] = $diffData['commonPart'];
        }

        uksort($mapping, function ($a, $b) {
            $lenA = strlen($a);
            $lenB = strlen($b);

            if ($lenA == $lenB) {
                return 0;
            } elseif ($lenA > $lenB) {
                return -1;
            } else {
                return 1;
            }
        });

        // maximise prefix length
        $optimizedMapping = array();

        foreach ($mapping as $oldPrefix => $newMapping) {
            foreach ($newMapping as $newPrefix => $commons) {
                for ($pos = 0; /* break inside */; $pos++) {
                    for ($current = 0; $current < count($commons); $current++) {
                        if (strlen($commons[$current]) <= $pos) {
                            break 2;
                        }

                        if ($current == 0) {
                            $char = $commons[$current][$pos];
                            continue;
                        }

                        if ($commons[$current][$pos] != $char) {
                            break 2;
                        }
                    }
                }

                $optOldPrefix = $oldPrefix . substr($commons[0], 0, $pos);
                $optNewPrefix = $newPrefix . substr($commons[0], 0, $pos);

                if (!isset($optimizedMapping[$optOldPrefix])) {
                    $optimizedMapping[$optOldPrefix] = array();
                }

                $optimizedMapping[$optOldPrefix][$optNewPrefix] = array_map(function ($val) use ($pos) {
                    return substr($val, $pos);
                }, $commons);
            }
        }

        return $optimizedMapping;
    }

    /**
     * return param table default
     *
     * @return array
     */
    public function getDefaultParamValue()
    {
        $result = array();

        foreach ($this->tables as $table) {
            $result[$table->getOriginalName()] = ParamFormTables::getParamItemValueFromData($table->getOriginalName(), $table->canBeExctracted(), $table->canBeExctracted());
        }

        return $result;
    }

    /**
     * return general tables names without prefix
     *
     * @return string[]
     */
    public static function getGeneralTablesNames()
    {
        return array(
            'commentmeta',
            'comments',
            'links',
            'options',
            'postmeta',
            'posts',
            'term_relationships',
            'term_taxonomy',
            'terms',
            'termmeta'
        );
    }

    /**
     * return multisite general tables without prefix
     *
     * @return string[]
     */
    public static function getMultisiteTables()
    {
        return array(
            'blogmeta',
            'blogs',
            'blog_versions',
            'registration_log',
            'signups',
            'site',
            'sitemeta'
        );
    }
}
