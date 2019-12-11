<?php
/**
 * Created by PhpStorm.
 * User: mfr
 * Date: 19-12-7
 * Time: 下午5:33
 */

namespace App\HttpController;


abstract class BaseBuilders
{
    public $with = [];

    public $data = null;

    public $one = [];

    public $many = [];

    public $tempWith = null;

    public $isCollection = false;

    public $subBuilders = [];

    public $parentBuilders = [];

    public abstract function find($params);

    public abstract function getList($params);

    private function initBuilder()
    {
        $this->data = null;
        $this->one = [];
        $this->many = [];
        $this->tempWith = null;
    }

    public function handleRelation($builder)
    {
        if (is_array($builder->data) && count($builder->data) > 0) {
            if (count($builder->one) > 0) {
                if ($builder->isCollection) {
                    foreach ($builder->data as $key => $dataItem) {
                        foreach ($builder->one as $oneItem) {
                            $builder->data[$key] = $builder->handleOne($builder->data[$key], $oneItem);
                        }
                    }
                } else {
                    $dataItem = $builder->data;
                    foreach ($builder->one as $oneItem) {
                        $dataItem = $builder->handleOne($dataItem, $oneItem);
                    }
                    $builder->data = $dataItem;
                }
            }
            if (count($builder->many) > 0) {
                if ($builder->isCollection) {
                    foreach ($builder->data as $key => $dataItem) {
                        foreach ($builder->many as $manyItem) {
                            $builder->data[$key] = $builder->handleMany($builder->data[$key], $manyItem);
                        }
                    }
                } else {
                    $dataItem = $builder->data;
                    foreach ($builder->many as $manyItem) {
                        $dataItem = $builder->handleMany($dataItem, $manyItem);
                    }
                    $builder->data = $dataItem;
                }
            }
            return $builder;
        }
    }

    public function handleWith($builder, $with)
    {
        $with = ucfirst($with);
        if (!method_exists($builder, $with)) {
            return null;
        }

        $builder->tempWith = $this->hump_to_line($with);
        $builder = $builder->$with();
        return $builder;
    }

    public function get($data)
    {
        $this->initBuilder();

        if (isset($data['data'])) {
            $this->data = &$data['data'];
        } else {
            $this->data = &$data;
        }

        $this->isCollection = !empty($this->data)
            ? array_key_exists(0, $this->data) : false;

        if ($this->with) {
            foreach ($this->with as $with) {
                $this->tempWith = null;
                $withArray = explode('.', $with);
                $builder = $this;
                if (count($withArray) > 0) {
                    $builderList = [];
                    $builderList[] = $builder;
                    foreach ($withArray as $withItem) {
                        $builder = $this->handleWith($builder, $withItem);
                        $builderList[] = $builder;
                    }

                    $builderList = array_reverse($builderList);
                    $tempBuilder = null;
                    for ($i = 0; $i < count($builderList); $i++) {
                        if ($i !== 0) {
                            if ($tempBuilder) {
                                $match = $tempBuilder->parentBuilder['match'];
                                $localKey = $tempBuilder->parentBuilder['localKey'];
                                $foreignKey = $tempBuilder->parentBuilder['foreignKey'];
                                $builderList[$i]->$match[$localKey]['foreign_data'] =
                                    $match ==='one'
                                        ? $this->handleOneForeignData($tempBuilder->data, $foreignKey)
                                        : $this->handleManyForeignData($tempBuilder->data, $foreignKey);
                            }
                            $tempBuilder = $builderList[$i]->handleRelation($builderList[$i]);
                        }
                    }
                }
            }
        }

        return $data;
    }

    private function handleMany($dataItem, $manyItem)
    {
        if (isset($manyItem['foreign_data'][$dataItem[$manyItem['local_key']]])) {
            $dataItem[$manyItem['with']] = $manyItem['foreign_data'][$dataItem[$manyItem['local_key']]];
        } else {
            $dataItem[$manyItem['with']] = null;
        }
        return $dataItem;
    }

    private function handleOne($dataItem, $oneItem)
    {
        if (isset($oneItem['foreign_data'][$dataItem[$oneItem['local_key']]])) {
            $dataItem[$oneItem['with']] = $oneItem['foreign_data'][$dataItem[$oneItem['local_key']]];
        } else {
            $dataItem[$oneItem['with']] = null;
        }
        return $dataItem;
    }

    public function handleOneForeignData($foreignData, $foreignKey)
    {
        $formattedForeignData = [];
        if (count($foreignData) > 0) {
            foreach ($foreignData as $data) {
                if (isset($data[$foreignKey])) {
                    $formattedForeignData[$data[$foreignKey]] = $data;
                }
            }
        }
        return $formattedForeignData;
    }

    public function handleManyForeignData($foreignData, $foreignKey)
    {
        $formattedForeignData = [];
        if (count($foreignData) > 0) {
            foreach ($foreignData as $data) {
                if (isset($data[$foreignKey])) {
                    $formattedForeignData[$data[$foreignKey]][] = $data;
                }
            }
        }
        return $formattedForeignData;
    }

    public function pluckData ()
    {
        $returnData = [];
        $isCollection = !empty($this->data)
            ? array_key_exists(0, $this->data) : false;
        if ($isCollection) {
            foreach ($this->data as $k => $v) {
                if (isset($v[$this->tempWith]) && $v[$this->tempWith]) {
                    if (array_key_exists(0, $v[$this->tempWith])) {
                        $returnData = array_merge($returnData, $v[$this->tempWith]);
                    } else {
                        $returnData[]= $v[$this->tempWith];
                    }
                }
            }
        } else {
            if (isset($this->data[$this->tempWith]) && $this->data[$this->tempWith]) {
                $returnData = $this->data[$this->tempWith];
            }
        }
        return $returnData;
    }

    public function matchOne($builder, $localKey, $foreignKey, $paramKey = null)
    {
        if (class_exists($builder) && $this->tempWith) {
            $builderItem = new $builder;
            $localKeys = $this->isCollection ? array_column($this->data, $localKey) : (isset($this->data[$localKey]) ? [$this->data[$localKey]] : []);
            $localKeys = array_unique($localKeys);

            $foreignData = $this->pluckData();
            if ($foreignData) {
                $builderItem->get($foreignData);
            } else {
                try {
                    $foreignData = $builderItem->getList([($paramKey ?? $localKey.'s') => $localKeys]);
                } catch(\Exception $e) {

                }
                $foreignData = (isset($foreignData['data']) ? $foreignData['data'] : $foreignData);
            }

            $builderItem->parentBuilder['match'] = 'one';
            $builderItem->parentBuilder['localKey'] = $localKey;
            $builderItem->parentBuilder['foreignKey'] = $foreignKey;

            $this->one[$localKey] = [
                'with' => $this->tempWith,
                'local_key' => $localKey,
                'foreign_key' => $foreignKey,
                'foreign_data' => $this->handleOneForeignData($foreignData, $foreignKey)
            ];
            return $builderItem;
        }

        return true;
    }

    public function matchMany($builder, $localKey, $foreignKey, $paramKey = null)
    {

        if (class_exists($builder) && $this->tempWith) {
            $builderItem = new $builder;
            $localKeys = $this->isCollection ? array_pluck($this->data, $localKey) : (isset($this->data[$localKey]) ? [$this->data[$localKey]] : []);
            $localKeys = array_unique($localKeys);

            $foreignData = $this->pluckData();
            if ($foreignData) {
                $builderItem->get($foreignData);
            } else {
                try {
                    $foreignData = $builderItem->getList([($paramKey ?? $localKey.'s') => $localKeys]);
                } catch (\Exception $e) {

                }
                $foreignData = isset($foreignData['data']) ? $foreignData['data'] : $foreignData;
            }

            $builderItem->parentBuilder['match'] = 'many';
            $builderItem->parentBuilder['localKey'] = $localKey;
            $builderItem->parentBuilder['foreignKey'] = $foreignKey;

            $this->many[$localKey] = [
                'with' => $this->tempWith,
                'local_key' => $localKey,
                'foreign_key' => $foreignKey,
                'foreign_data' => $this->handleManyForeignData($foreignData, $foreignKey)
            ];
            return $builderItem;
        }

        return true;
    }

    public function with(...$args)
    {
        $countArgs = count($args);
        if ($countArgs === 1) {
            $argument = array_pop($args);
            if (!is_array($argument)) {
                $args = [$argument];
            } else {
                $args = $argument;
            }
        }
        $this->with = $args;
        return $this;
    }

    public function hump_to_line($str, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $str));
    }

}