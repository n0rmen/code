function Bench(nbIterations){
	this.nbIterations = nbIterations;
	this.processes = [];
	this.executionTime = 0;
}	
Bench.prototype.addProcess = function addProcess(name, func, parameters){
	if(typeof name == "undefined") throw "Error: process name is not defined";
	if(typeof func == "undefined") throw "Error: process function is not defined";
	if(!(parameters instanceof Array)) parameters = [parameters];
	
	this.processes.push({'name' : name, 'func' : func, 'parameters' : parameters});
}

Bench.prototype.run = function run(){
	var process,startTime,endTime;
	for(var i in this.processes){
		process = this.processes[i];
		startTime = Date.now()
		for(var i=0,l=this.nbIterations; i<l; i++){
			process.func.apply(process.parameters);
		}
		endTime = Date.now()
		process.executionTime = (endTime - startTime) / 1000;
	}
	this.processes.sort(function(a, b){
		return (a.executionTime < b.executionTime) ? -1 : 1;
	}); 
}

Bench.prototype.html = function html(){
	var process,html;
	html = "<table border=\"1\">";
	html += "<tr><td>Process</td><td>Execution time (" + this.nbIterations + " iterations)</td></tr>";
	for(var i in this.processes){
		process = this.processes[i];
		html += "<tr><td>" + process.name + "</td><td>" + process.executionTime.toFixed(3) + "s</td></tr>";
	}
	html += "</table>";
	return html;
}

Bench.prototype.json = function json(){
	return JSON.parse(JSON.stringify(this.processes));
}
