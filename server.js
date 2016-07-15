var http = require('http');
var fs = require('fs');
//var path = require('path');

//Creation du serveur http
var app = http.createServer(function (req, res) {
  res.writeHead(200);
  res.end('Salut tout le monde !');
  //file.serve(req, res);
}).listen(9090);

var io = require('socket.io').listen(app);
var path = null;
var buffer = null;

// Connexion du socket
io.sockets.on('connection', function (socket){
	console.log('Un client est connecté ');
    // Evenement pour sauvegarder le travail
	socket.on('saveWork', function(file) {
	buffer = new Buffer(file);
    var min = 1;
    var max = 20000000;
    var randomId = Math.floor(Math.random() * (max - min + 1) + min);
    path = 'public/savedWork/file'+randomId+'.html';
    // Créer fichier et l'ouvrir en mode ecriture
    fs.open(path, 'w', function(err, fd) {
      if (err) {
        throw 'error opening file: ' + err;
        }
    // Ecrire sur le fichier
    fs.write(fd, buffer, 0, buffer.length, null, function(err) {
      if (err) {
          // Confirmer la sauvegarde
          socket.emit('saveWorkStatus', false);
          throw 'error writing file: ' + err;
      }
        fs.close(fd, function() {
            console.log('file written');
            socket.emit('saveWorkStatus', true);
        })
    });
});
    })
    socket.on('getTemplate', function(name) {
        console.log("okkkkkkkkkkkkkkkk"+name);
        //var filePath = path.join(__dirname, 'start.html');
        path = 'public/savedWork/test1.html';
        fs.readFile(path, {encoding: 'utf-8'}, function(err,data){
            if (!err){
                console.log('received data: ' + data);
                socket.emit('setTemplate', data);
               // response.writeHead(200, {'Content-Type': 'text/html'});
               // response.write(data);
               // response.end();
            }else{
                console.log(err);
            }
        })
    });
    socket.on('importHtmlServer', function(url) {

        url = __dirname+url;

        fs.readFile(url,  function(err,data){

            if (!err){
                console.log(data+"");
                socket.emit('importHtmlClient',  {content:data+""});

            }else{
                console.log(err);
            }
        })
    });
});

