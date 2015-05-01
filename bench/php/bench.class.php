<?php

class Bench{
	public $nbIterations;
	public $processes = [];
	public $executionTime;
	
	public function __construct($nbIterations=10){
		$this->nbIterations = $nbIterations;
	}
	
	public function addProcess($name=null, $func=null, $parameters=[]){
		if(empty($name)) throw new Exception("Error: process name is not defined");
		if(empty($func)) throw new Exception("Error: function name is not defined");
		if(!is_array($parameters)) $parameters = array($parameters);
		
		$this->processes[] = (object) array('name' => $name, 'func' => $func, 'parameters' => $parameters);
	}
	
	public function run(){
		foreach($this->processes as $process){
			$start_time = microtime(true);
			for($i=0,$l=$this->nbIterations; $i<$l; $i++){
				call_user_func_array($process->func, $process->parameters);
			}
			$end_time = microtime(true);
			$process->executionTime = $end_time - $start_time;
		}
		usort($this->processes, function($a, $b){
			return ($a->executionTime < $b->executionTime) ? -1 : 1;
		}); 
	}
	
	public function html(){
		$html = "<table border=\"1\">";
		$html .= "<tr><td>Process</td><td>Execution time (".$this->nbIterations." iterations)</td></tr>";
		foreach($this->processes as $process){
			$html .= "<tr><td>".$process->name."</td><td>".number_format($process->executionTime, 3)."s</td></tr>";
		}
		$html .= "</table>";
		return $html;
	}
	
	public function json(){
		return json_encode($this->processes);
	}
}
