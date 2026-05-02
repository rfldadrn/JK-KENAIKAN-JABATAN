<?php
/**
 * Validation.php - Input Validation Helper
 * Provides validation methods for form inputs
 */

class Validation
{
    private $errors = [];

    /**
     * Check if field is required
     * @param string $field
     * @param mixed $value
     * @param string $label
     * @return Validation
     */
    public function required($field, $value, $label = null)
    {
        $label = $label ?? $field;
        if (empty($value) && $value !== '0') {
            $this->errors[$field] = "{$label} harus diisi";
        }
        return $this;
    }

    /**
     * Validate email format
     * @param string $field
     * @param string $value
     * @param string $label
     * @return Validation
     */
    public function email($field, $value, $label = null)
    {
        $label = $label ?? $field;
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "{$label} harus berupa email yang valid";
        }
        return $this;
    }

    /**
     * Validate minimum length
     * @param string $field
     * @param string $value
     * @param int $min
     * @param string $label
     * @return Validation
     */
    public function minLength($field, $value, $min, $label = null)
    {
        $label = $label ?? $field;
        if (!empty($value) && strlen($value) < $min) {
            $this->errors[$field] = "{$label} minimal {$min} karakter";
        }
        return $this;
    }

    /**
     * Validate maximum length
     * @param string $field
     * @param string $value
     * @param int $max
     * @param string $label
     * @return Validation
     */
    public function maxLength($field, $value, $max, $label = null)
    {
        $label = $label ?? $field;
        if (!empty($value) && strlen($value) > $max) {
            $this->errors[$field] = "{$label} maksimal {$max} karakter";
        }
        return $this;
    }

    /**
     * Validate numeric value
     * @param string $field
     * @param mixed $value
     * @param string $label
     * @return Validation
     */
    public function numeric($field, $value, $label = null)
    {
        $label = $label ?? $field;
        if (!empty($value) && !is_numeric($value)) {
            $this->errors[$field] = "{$label} harus berupa angka";
        }
        return $this;
    }

    /**
     * Validate minimum value
     * @param string $field
     * @param mixed $value
     * @param int|float $min
     * @param string $label
     * @return Validation
     */
    public function min($field, $value, $min, $label = null)
    {
        $label = $label ?? $field;
        if (!empty($value) && $value < $min) {
            $this->errors[$field] = "{$label} minimal {$min}";
        }
        return $this;
    }

    /**
     * Validate maximum value
     * @param string $field
     * @param mixed $value
     * @param int|float $max
     * @param string $label
     * @return Validation
     */
    public function max($field, $value, $max, $label = null)
    {
        $label = $label ?? $field;
        if (!empty($value) && $value > $max) {
            $this->errors[$field] = "{$label} maksimal {$max}";
        }
        return $this;
    }

    /**
     * Validate date format
     * @param string $field
     * @param string $value
     * @param string $format
     * @param string $label
     * @return Validation
     */
    public function date($field, $value, $format = 'Y-m-d', $label = null)
    {
        $label = $label ?? $field;
        if (!empty($value)) {
            $d = DateTime::createFromFormat($format, $value);
            if (!$d || $d->format($format) !== $value) {
                $this->errors[$field] = "{$label} format tanggal tidak valid";
            }
        }
        return $this;
    }

    /**
     * Validate if value matches another field
     * @param string $field
     * @param mixed $value
     * @param mixed $matchValue
     * @param string $label
     * @return Validation
     */
    public function match($field, $value, $matchValue, $label = null)
    {
        $label = $label ?? $field;
        if ($value !== $matchValue) {
            $this->errors[$field] = "{$label} tidak cocok";
        }
        return $this;
    }

    /**
     * Validate unique value in database
     * @param string $field
     * @param mixed $value
     * @param string $table
     * @param string $column
     * @param int $exceptId
     * @param string $label
     * @return Validation
     */
    public function unique($field, $value, $table, $column, $exceptId = null, $label = null)
    {
        $label = $label ?? $field;
        if (!empty($value)) {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
            
            if ($exceptId) {
                $sql .= " AND id != :id";
            }
            
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':value', $value);
            
            if ($exceptId) {
                $stmt->bindValue(':id', $exceptId);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            
            if ($result->count > 0) {
                $this->errors[$field] = "{$label} sudah digunakan";
            }
        }
        return $this;
    }

    /**
     * Validate file upload
     * @param string $field
     * @param array $file
     * @param array $allowedTypes
     * @param int $maxSize
     * @param string $label
     * @return Validation
     */
    public function file($field, $file, $allowedTypes = [], $maxSize = MAX_FILE_SIZE, $label = null)
    {
        $label = $label ?? $field;
        
        if (isset($file['error']) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            // Check if upload was successful
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $this->errors[$field] = "Gagal upload {$label}";
                return $this;
            }
            
            // Check file size
            if ($file['size'] > $maxSize) {
                $maxMB = $maxSize / (1024 * 1024);
                $this->errors[$field] = "{$label} maksimal " . number_format($maxMB, 1) . " MB";
                return $this;
            }
            
            // Check file type
            if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
                $this->errors[$field] = "{$label} tipe file tidak diizinkan";
            }
        }
        
        return $this;
    }

    /**
     * Add custom error
     * @param string $field
     * @param string $message
     */
    public function addError($field, $message)
    {
        $this->errors[$field] = $message;
    }

    /**
     * Check if validation passed
     * @return bool
     */
    public function passed()
    {
        return empty($this->errors);
    }

    /**
     * Check if validation failed
     * @return bool
     */
    public function failed()
    {
        return !$this->passed();
    }

    /**
     * Get all errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get first error message
     * @return string|null
     */
    public function getFirstError()
    {
        return !empty($this->errors) ? reset($this->errors) : null;
    }

    /**
     * Get error for specific field
     * @param string $field
     * @return string|null
     */
    public function getError($field)
    {
        return isset($this->errors[$field]) ? $this->errors[$field] : null;
    }
}
