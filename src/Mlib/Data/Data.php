<?php
namespace Mlib\Data;

class Data {
	protected $details;
	protected $name;
	protected $keys;
	protected $target_directory;
	
	protected $mysql_key_strings = array(
		'primary' => 'PRIMARY KEY',
		'unique' => 'UNIQUE',
		'index' => 'INDEX'
	);
	
	protected $mysql_types = array(
		'integer' => 'int',
		'string' => 'varchar',
		'timestamp' => 'timestamp'
	);
	
	public function __construct(DataConfigInterface $config, $target_directory=false) {
		$this->name = $config->name();
		$this->details = $config->details();
		$this->keys = $config->keys();
		
		// If you set target directory, store a file version of this also
		if($target_directory) {
			$this->target_directory = $target_directory;
		}
	}
	
	/**
	 * Generate schema for a table. Makes files and returns text in api format
	 */
	public function generate_schema() {
		$schema = "CREATE TABLE `".$this->name."` (\n";
		
		for($i = 0; $i < count($this->details); $i++) {
			$schema .= "`".$this->details[$i]['name']."` ";
			$schema .= $this->type($this->details[$i]);
			$schema .= $this->options($this->details[$i]['options']);
			$schema .= ",\n";
		}
		
		$schema .= $this->table_keys();
		$schema .= ')';
		$schema .= $this->engine();
		
		return $schema;
	}
	
	protected function type(Array $details) {
		$schema = $this->mysql_types[$details['type']];
		switch($details['type']) {
			case 'string':
			case 'integer':
				$schema .= "(".$details['options']['length'].")";
				break;
		}
		return $schema.' ';
	}
	
	protected function options(Array $options) {
		$schema = 'NOT NULL';
		if(in_array('autoincrement', $options)) {
			$schema .= ' AUTO_INCREMENT';
		}
		
		if(isset($options['default'])) {
			$schema .= ' DEFAULT '.strtoupper($options['default']);
		}
		return $schema;
	}
	
	protected function table_keys() {
		$schema = '';
		for($i = 0; $i < count($this->keys); $i++) {
			$schema .= $this->mysql_key_strings[$this->keys[$i]['type']];
			
			if($this->keys[$i]['type'] == 'index') {
				$schema .= " `".$this->keys[$i]['field']."`";
			}
			
			$schema .= " (`".$this->keys[$i]['field']."`)";
			if($i+1<count($this->keys)) {
				$schema .= ",";
			}
			$schema .= "\n";
		}
		return $schema;
	}
	
	protected function engine() {
		return 'ENGINE=InnoDB';
	}
}