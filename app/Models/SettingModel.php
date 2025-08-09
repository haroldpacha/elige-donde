<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['setting_key', 'setting_value'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all settings as an associative array.
     *
     * @return array
     */
    public function getSettings(): array
    {
        $settingsArray = [];
        $settings = $this->findAll();

        foreach ($settings as $setting) {
            $settingsArray[$setting['setting_key']] = $setting['setting_value'];
        }

        return $settingsArray;
    }

    /**
     * Save a batch of settings.
     *
     * @param array $data
     * @return void
     */
    public function saveSettings(array $data)
    {
        foreach ($data as $key => $value) {
            $builder = $this->db->table($this->table);
            $builder->where('setting_key', $key);
            $exists = $builder->get()->getRow();

            if ($exists) {
                $this->where('setting_key', $key)->set(['setting_value' => $value])->update();
            } else {
                $this->insert(['setting_key' => $key, 'setting_value' => $value]);
            }
        }
    }
}
