function testWorker(args) {
    postMessage(args);
}
this.onmessage = function(event) {
    console.log(event);
}