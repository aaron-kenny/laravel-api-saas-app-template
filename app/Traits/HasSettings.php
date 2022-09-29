<?php

namespace App\Traits;

use Exception;
use App\Contracts\SettingsManagerContract;
use App\Exceptions\SettingsException;
use App\Managers\SettingsManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;

/**
 * Trait HasSettingsField
 * @package Glorand\Model\Settings\Traits
 * @property array $settings
 * @property string $settingsFieldName
 * @property boolean $persistSettings
 */
trait HasSettings
{
    private $persistSettings = true;

    public $settingsFieldName = 'settings';

    public function getDefaultSettings(): array
    {
        if (property_exists($this, 'defaultSettings')) {
            return Arr::wrap($this->defaultSettings);
        }

        return [];
    }

    protected static function bootHasSettingsField()
    {
        static::saving(function ($model) {
            /** @var self $model */
            $model->fixSettingsValue();
        });
    }

    /**
     * @return \Glorand\Model\Settings\Contracts\SettingsManagerContract
     * @throws ModelSettingsException
     */
    public function settings(): SettingsManagerContract
    {
        return new SettingsManager($this);
    }

    public function fixSettingsValue()
    {
        $settingsFieldName = $this->getSettingsFieldName();
        $attributes = $this->getAttributes();
        if (Arr::has($attributes, $settingsFieldName)) {
            if (is_array($this->$settingsFieldName)) {
                $this->$settingsFieldName = json_encode($this->$settingsFieldName);
            }
        }
    }

    /**
     * @return array
     * @throws ModelSettingsException
     */
    public function getSettingsValue(): array
    {
        $settingsFieldName = $this->getSettingsFieldName();
        if (!$this->hasSettingsField()) {
            throw new SettingsException("Unknown field ($settingsFieldName) on table {$this->getTable()}");
        }

        return json_decode($this->getAttributeValue($settingsFieldName) ?? '[]', true);
    }

    /**
     * @return string
     */
    public function getSettingsFieldName(): string
    {
        return $this->settingsFieldName ?? 'settings';
    }

    /**
     * @return bool
     */
    public function isPersistSettings(): bool
    {
        return boolval($this->persistSettings);
    }

    /**
     * @param bool $val
     */
    public function setPersistSettings(bool $val = true)
    {
        $this->persistSettings = $val;
    }


    /**
     * @return mixed
     * @throws \Glorand\Model\Settings\Exceptions\ModelSettingsException
     */
    private function hasSettingsField()
    {
        return Schema::hasColumn($this->getTable(), $this->getSettingsFieldName());
    }

    abstract public function getTable();

    abstract public function getAttributes();

    abstract public function getAttributeValue($key);
}