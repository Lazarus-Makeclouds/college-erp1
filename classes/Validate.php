<?php
class Validate {
    private $_passed = false;
    private $_errors = [];
    private $_db = null;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = array()) {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {

                $value =  isset($source[$item]) ? $source[$item] : '';
                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } else if (!empty($value) || $item === 'Image') {

                    switch ($rule) {
                        case 'unique':
                            $check = $this->_db->get($rule_value,[$item, '=', $value]);
                            if($check->count()){
                                $this->addError("{$item} already registered");
                            }
                        break;

                        case 'length':
                            if (is_numeric($value)) {
                                $phstr = "$value";
                                if (!preg_match("/^\d{10}$/", $phstr)) {
                                    $this->addError("Invalid {$item}");
                                }
                            } else {
                                $this->addError("{$item} must be a valid number");
                            }
                        break;

                        case 'valid_email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("Invalid {$item}");
                            }
                        break;

                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->addError("{$item} must match {$rule_value}");
                            }
                        break;

                        case 'ext':
                            if (isset($_FILES[$item]) && $_FILES[$item]['error'] === 0) {
                                $filename = $_FILES[$item]['name'];
                                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                if (!array_key_exists($ext, $rule_value)) {
                                    $this->addError("{$item} must be a valid file type: " . implode(', ', array_keys($rule_value)));
                                }
                            }
                        break;

                        case 'size':
                            if (isset($_FILES[$item]) && $_FILES[$item]['error'] === 0) {
                                if ($_FILES[$item]['size'] > $rule_value) {
                                    $this->addError("Image must be smaller than " . ($rule_value / 1024 / 1024) . "MB");
                                }
                            }
                        break;

                        case 'dim':
                            if (isset($_FILES[$item]) && $_FILES[$item]['error'] === 0) {
                                $img = null;
                                $fileContent = $_FILES[$item]["tmp_name"];
                                $filetype = $_FILES[$item]["type"];
                                if ($filetype == "image/jpeg" || $filetype == "image/jpg") {
                                    $img = imagecreatefromjpeg($fileContent);
                                } elseif ($filetype == "image/png") {
                                    $img = imagecreatefrompng($fileContent);
                                } elseif ($filetype == "image/gif") {
                                    $img = imagecreatefromgif($fileContent);
                                }

                                if ($img) {
                                    $imgWidth = imagesx($img);
                                    $imgHeight = imagesy($img);
                                    if($imgWidth >= 600 || $imgHeight >= 600){
                                        $this->addError("Image dimensions must be smaller than 600x600 pixels");
                                    }
                                    imagedestroy($img);
                                }
                            }
                        break;
                    }
                }
            }
        }

        if (empty($this->errors())) {
            $this->_passed = true;
        }

        return $this;
    }

    private function addError($error) {
        $this->_errors[] = $error;
    }

    public function errors() {
        return $this->_errors;
    }

    public function passed() {
        return $this->_passed;
    }
}
