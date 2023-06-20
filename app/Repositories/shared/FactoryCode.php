<?php
namespace App\Repositories\shared;

class FactoryCode
{
    private $documentCode = "";
    private $company_id = "";
    private $budget_type = "";
    private $payment_type_id = "";
    private $currency = "";
    private $name = "";
    private $code = "";
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function __get($name)
    {
        return $this->$name;
    }
    public function create()
    {
        //Đây là thứ tự chuẩn , nếu thay đổi ảnh hưởng đến code trong bảng hạn mức
        $this->code ="";
        $this->addString($this->documentCode);
        $this->addString($this->company_id);
        $this->addString($this->budget_type);
        $this->addString($this->payment_type_id);
        $this->addString($this->currency);
        $this->addString($this->name);
        return $this->code;
    }
    private function addString($value)
    {     
      
        if ($value !== "") {
            if ($this->code == "") {
                $this->code = $value;
            } else {
                $this->code = $this->code . "_" . $value;
            }
        }

    }
}
