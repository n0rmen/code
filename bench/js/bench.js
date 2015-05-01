function method1(arg){
	console.log(arg);
}

function method2(arg){
	console.log(arg);
}
var arg = 0;

var bench = new Bench(10);
bench.addProcess("method1", method1, arg);
bench.addProcess("method2", method2, arg);
bench.run();
document.write(bench.html());
