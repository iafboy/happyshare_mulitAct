<?php

class Entry
{

    // original entry name
    private $entry;

    // entry name for label
    private $entry_name;

    // filter name for inputs
    private $filter_name;

    // filter values for inputs
    private $filter_value;

    // which column is this entry in
    // start with 0, end with column_count -1
    private $column;

    // which input type it is
    // radio
    // select
    // text
    // number
    // checkbox
    // date
    // datetime
    private $type = 'text';

    // if current entry is an select, this field is used to display possible options
    private $options;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getEntryName()
    {
        return $this->entry_name;
    }

    /**
     * @return string
     */
    public function getFilterName()
    {
        return $this->filter_name;
    }

    /**
     * Entry constructor.
     */
    public function __construct($entry = null, $column)
    {
        if (!empty($entry)) {
            $this->entry = $entry;
            $this->entry_name = 'entry_' . $entry;
            $this->filter_name = 'filter_' . $entry;
            $this->column = $column;
        }
    }

    /**
     * @return null
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * @param null $entry
     */
    public function setEntry($entry)
    {
        if (!empty($entry)) {
            $this->entry = $entry;
            $this->entry_name = 'entry_' . $entry;
            $this->filter_name = 'filter_' . $entry;
        }
    }

    /**
     * @return mixed
     */
    public function getFilterValue()
    {
        return $this->filter_value;
    }

    /**
     * @param mixed $filter_value
     */
    public function setFilterValue($filter_value)
    {
        $this->filter_value = $filter_value;
    }

    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param mixed $column
     */
    public function setColumn($column)
    {
        $this->column = $column;
    }
}

class Column
{

    private $name;

    private $text;

    private $is_id;

    private $is_flag;

    /**
     * Column constructor.
     * @param $name
     * @param $text
     * @param $is_id
     * @param $is_flag
     */
    public function __construct($name, $text, $is_id = false, $is_flag = false)
    {
        $this->name = $name;
        $this->text = $text;
        $this->is_id = $is_id;
        $this->is_flag = $is_flag;
    }


}

class Theader
{

    private $columns;

    private $show_flag = false;

    /**
     * Theader constructor.
     * @param $columns
     * @param bool $show_flag
     */
    public function __construct($columns = array(), $lans, $show_flag = false)
    {
        $this->columns = array();
        foreach ($columns as $column) {
            $c = new Column($column, $lans[$column], false, false);
            $this->columns[] = $c;
        }
        $this->show_flag = $show_flag;
    }

    public function toArray()
    {
        $array = array();
        $columns = array();
        foreach ($this->columns as $column) {
            $c = array();
            $c['name'] = $column->getName();
            $c['text'] = $column->getText();
            $columns[] = $c;
        }
        $array['columns'] = $columns;
        $array['show_flag'] = $this->show_flag;
        return $array;
    }


}

class Button
{

    private $text;

    private $name;

    private $class_name;

    /**
     * Button constructor.
     * @param $text
     * @param $name
     */
    public function __construct($name, $text, $class_name = '')
    {
        $this->name = $name;
        $this->text = $text;
        $this->class_name = $class_name;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->class_name;
    }

    /**
     * @param string $class_name
     */
    public function setClassName($class_name)
    {
        $this->class_name = $class_name;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}

class EntriesForm
{

    private $entry_column_count;

    private $entry_column_size;

    private $entries = array();

    private $btns = array();

    private $entry_names = array();

    private $filter_names = array();

    private $form_id = '';

    private $base_url = '';

    private $export_url = '';

    private $page = 1;

    private $route = '';

    private $token = '';

    private $limit;

    private $start;

    private $order;

    /**
     * @return string
     */
    public function getExportUrl()
    {
        return $this->export_url;
    }

    /**
     * @param string $export_url
     */
    public function setExportUrl($export_url)
    {
        $this->export_url = $export_url;
    }



    /**
     * @return array
     */
    public function getEntryNames()
    {
        return $this->entry_names;
    }

    /**
     * @param array $entry_names
     */
    public function setEntryNames($entry_names)
    {
        $this->entry_names = $entry_names;
    }

    /**
     * @return array
     */
    public function getFilterNames()
    {
        return $this->filter_names;
    }

    /**
     * @param array $filter_names
     */
    public function setFilterNames($filter_names)
    {
        $this->filter_names = $filter_names;
    }


    /**
     * @return string
     */
    public function getFormId()
    {
        return $this->form_id;
    }

    /**
     * @param string $form_id
     */
    public function setFormId($form_id)
    {
        $this->form_id = $form_id;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @param string $base_url
     */
    public function setBaseUrl($base_url)
    {
        $this->base_url = $base_url;
    }


    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }


    /**
     * EntriesForm constructor.
     * @param int $entry_column_count
     */
    public function __construct($entry_column_count = 4, $entries = array())
    {
        $this->entry_column_count = $entry_column_count;
        $this->entry_column_size = 12 / $entry_column_count;
        $this->setEntries($entries);
    }

    /**
     * @param array $array
     * ['seg1','seg2']
     *
     *
     * @param array $list_array
     * [
     * 'seg1'=>
     *         ['value1'=>'text1','value2'=>'text2']
     *
     * 'seg2'=>
     *         ['value1'=>'text1','value2'=>'text2']
     *
     * ]
     *
     */
    public function setSelectTypeEntries($array = array(), $list_array = array())
    {
        foreach ($array as $name) {
            $entry = $this->entries[$name];
            $entry->setType('select');
            $entry->setOptions($list_array[$name]);
        }
    }

    public function setEntriesInputType($name_array = array(), $type)
    {
        foreach ($name_array as $name) {
            if (strpos($name, '_start') === false && strpos($name, '_end') === false) {
                $entry = $this->entries[$name];
                $entry->setType($type);
            // for combine model
            } else {
                if (strpos($name, '_start') !== false) {
                    $name = str_replace('_start', '', $name);
                    $entry_arr = $this->entries[$name];
                    foreach ($entry_arr as $entry) {
                        $entry->setType($type);
                    }
                } else if (strpos($name, '_end') !== false) {
                    $name = str_replace('_end', '', $name);
                    $entry_arr = $this->entries[$name];
                    foreach ($entry_arr as $entry) {
                        $entry->setType($type);
                    }
                }
            }
        }
    }

    /**
     * @param array $btns
     */
    public function setButtons($btns = array())
    {
        $this->btns = $btns;
    }

    private function setEntries($names = array())
    {
        $this->entry_names = $names;
        $this->filter_names = array();
        foreach ($names as $name) {
            // for combine mode
            if (is_array($name)) {
                $arr = array();
                foreach ($name as $n) {
                    $arr[] = 'filter_' . $n;
                }
                $this->filter_names[] = $arr;
            } else {
                $this->filter_names[] = 'filter_' . $name;
            }
        }
        $index = 0;
        foreach ($names as $name) {
            // for combine mode
            if (is_array($name)) {
                $arr = array();
                $_name = '';
                foreach ($name as $n) {
                    $entry = new Entry($n, $index % $this->entry_column_count);
                    $entry->setType('number');
                    $arr[] = $entry;
                    $_name = $n;
                }
                $_name = str_replace('_start', '', $_name);
                $_name = str_replace('_end', '', $_name);
                $this->entries[$_name] = $arr;
            } else {
                $entry = new Entry($name, $index % $this->entry_column_count);
                $this->entries[$name] = $entry;
            }
            $index++;
        }
    }

    public function setEntriesValue($values = array())
    {
        foreach ($values as $key => $value) {
            $this->setEntryValue($key, $value);
        }
    }

    private function setEntryValue($name, $value)
    {
        if (!empty($name)) {
            if ($name === 'page' || $name === 'sort' || $name === 'order' || $name === 'start' || $name === 'limit') {
                return;
            }
            $name = str_replace('filter_', '', $name);
            if (strpos($name, '_start') === false && strpos($name, '_end') === false) {
                $entry = $this->entries[$name];
                $entry->setFilterValue($value);
            // for combine model
            } else {
                if (strpos($name, '_start') !== false) {
                    $name = str_replace('_start', '', $name);
                    $entry_arr = $this->entries[$name];
                    $entry_arr[0]->setFilterValue($value);
                } else if (strpos($name, '_end') !== false) {
                    $name = str_replace('_end', '', $name);
                    $entry_arr = $this->entries[$name];
                    $entry_arr[1]->setFilterValue($value);
                }
            }
        }
    }

    /**
     * convert from object to array
     * @return array
     */
    public function toArray($lans)
    {
        $array = array();
        $array['entry_column_count'] = $this->entry_column_count;
        $array['entry_column_size'] = $this->entry_column_size;
        $array['form_id'] = $this->form_id;
        $array['base_url'] = $this->base_url;
        $array['export_url'] = $this->export_url;
        $array['route'] = $this->route;
        $array['token'] = $this->token;
        $entries = array();
        for ($i = 0; $i < $this->entry_column_count; $i++) {
            $entries[$i . ''] = array();
        }

        foreach ($this->entries as $name => $value) {
            // for combine mode
            if (is_array($value)) {
                $column = $value[0]->getColumn();
                $entry_arr = array();
                for ($x = 0; $x < 2; $x++) {
                    $entry = array();
                    $entry['column'] = $value[$x]->getColumn();
                    $entry['entry'] = $value[$x]->getEntry();
                    $entry['entry_text'] = $lans[$value[$x]->getEntry()];
                    $entry['entry_name'] = $lans[$value[$x]->getEntryName()];
                    $entry['filter_name'] = $value[$x]->getFilterName();
                    $entry['filter_value'] = $value[$x]->getFilterValue();
                    $entry['type'] = $value[$x]->getType();
                    $entry['options'] = $value[$x]->getOptions();
                    $entry_arr[] = $entry;
                }
                $entries[$column . ''][] = $entry_arr;
            } else {
                $column = $value->getColumn();
                $entry = array();
                $entry['column'] = $value->getColumn();
                $entry['entry'] = $value->getEntry();
                $entry['entry_text'] = $lans[$value->getEntry()];
                $entry['entry_name'] = $lans[$value->getEntryName()];
                $entry['filter_name'] = $value->getFilterName();
                $entry['filter_value'] = $value->getFilterValue();
                $entry['type'] = $value->getType();
                $entry['options'] = $value->getOptions();
                $entries[$column . ''][] = $entry;
            }
        }
        $array['entries'] = $entries;
        $btns = array();
        foreach ($this->btns as $btn) {
            $name = $btn->getName();
            $btns[$name] = array();
            $btns[$name]['name'] = $btn->getName();
            $btns[$name]['text'] = $btn->getText();
            $btns[$name]['class_name'] = $btn->getClassName();
        }
        $array['btns'] = $btns;
        return $array;
    }

    /***
     * get entry name array
     * @return array
     */
    public function getEntryNameArray()
    {
        return $this->entry_names;
    }

    /**
     * get filter name array
     * @return array
     */
    public function getFilterNameArray()
    {
        return $this->filter_names;
    }
}