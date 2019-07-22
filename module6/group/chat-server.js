// Require the packages we will use:
var http = require("http"),
	socketio = require("socket.io"),
	fs = require("fs");

// Listen for HTTP connections.  This is essentially a miniature static file server that only serves our one file, client.html:
var app = http.createServer(function (req, resp) {
	// This callback runs when a new connection is made to our HTTP server.

	fs.readFile("client.html", function (err, data) {
		// This callback runs when the client.html file has been read from the filesystem.

		if (err) return resp.writeHead(500);
		resp.writeHead(200);
		resp.end(data);
	});
});
app.listen(3456);


// since we want to track the banned users of each room, so we are using a dictionary here
// and room name as the key, and banned users as the dictionary
var bannedUsers = {};
var allRoomUsers = {};

// this dictionary is used to track a room's password
// set the password to null if the room does not have a password
var allRoomsPW = {};

var userLocation = {};
var roomOwner = {};

// keep tracking the previous room user stays
var userPreviousRoom = {}

var allRooms = [];
var allUsers = [];

var userSocketId = {};

// Do the Socket.IO magic:
var io = socketio.listen(app);
io.sockets.on("connection", function (socket) {
	// This callback runs when a new Socket.IO connection is established.
	socket.on("newUser", function (data) { // process new user's username and send it back to server side
		var userInvalid = false;
		var userExist = false;
		var username = data["username"];
		if (username == null || username.replace(" ", "") == "") {
			userInvalid = true;
		}
		if (!userInvalid) {
			for (var u in allUsers) {
				if (allUsers[u] == username) {
					userInvalid = true;
					userExist = true;
				}
			}
			if (!userInvalid) {
				allUsers.push(username);
				socket.currentUser = username;
				socket.currentRoom = "lobby";
				userLocation[socket.currentUser] = "lobby";
				if (allRoomUsers["lobby"] == null) {
					allRoomUsers["lobby"] = [];
				}
				userPreviousRoom[username] = null;
				allRoomUsers["lobby"].push(username);
				socket.join("lobby");

				userSocketId[username] = socket.id;

				io.sockets.in(socket.currentRoom).emit("joinRoom", { currentUser: socket.currentUser, currentRoom: socket.currentRoom, currentRoomPW: null, currentUsers: allUsers, joinRoom: false });
			}
		}
		socket.emit("getNewUser", { users: allUsers, username: username, exist: userExist, invalid: userInvalid });
	});

	socket.on("newRoom", function (data) { // process new Room info and send it back to server side
		var roomExist = false;
		var rmName = data["roomName"];
		for (var r in allRooms) {
			if (allRooms[r] == rmName) {
				roomExist = true;
			}
		}
		if (!roomExist) {
			roomOwner[rmName] = socket.currentUser;
			allRooms.push(rmName);
			allRoomsPW[rmName] = null;
			allRoomUsers[rmName] = [];
		}
		io.sockets.emit("getNewRoom", { rooms: allRooms, roomName: rmName, exist: roomExist });
	});

	socket.on("newRoomPW", function (data) {// process new Private room info and send it back to server side
		var roomExist = false;
		var rmName = data["roomName"];
		var rmPW = data["roomPW"];
		for (var r in allRooms) {
			if (allRooms[r] == rmName) {
				roomExist = true;
			}
		}
		if (!roomExist) {
			roomOwner[rmName] = socket.currentUser;
			allRooms.push(rmName);
			allRoomsPW[rmName] = rmPW;
			allRoomUsers[rmName] = []
		}
		io.sockets.emit("getNewRoom", { rooms: allRooms, roomName: rmName, exist: roomExist });
	});

	socket.on("removeRoom", function (data) { // process remove room info and send it back to server side
		var rmName = data["roomName"];
		var roomExist = false;
		for (var r in allRooms) {
			if (allRooms[r] == rmName) {
				roomExist = true;
			}
		}
		if (!roomExist) {
			console.log("The room you want to delete does not exist!");
		}
		else {
			if (socket.currentRoom == rmName) {
				var owner = roomOwner[rmName];
				if (socket.currentUser == owner) {
					// remove room name from allRooms
					allRooms.splice(allRooms.indexOf(rmName), 1);
					// remove everything associated with the room
					delete roomOwner[rmName];
					delete allRoomsPW[rmName];
					delete bannedUsers[rmName];
					for (var u in allRoomUsers[rmName]) {
						userPreviousRoom[allRoomUsers[rmName]] = null;
						userLocation[allRoomUsers[rmName][u]] = "lobby";
						allRoomUsers["lobby"].push(allRoomUsers[rmName][u]);
					}
					io.sockets.emit("joinRoom", { currentUser: allRoomUsers[rmName][u], currentRoom: "lobby", currentRoomPW: null, currentUsers: allRoomUsers["lobby"], joinRoom: false });
					delete allRoomUsers[rmName];
					io.sockets.emit("removed", { currentRoom: rmName });
				}
				else {
					console.log("You are not the owner of the room, and you cannot delete this room");
				}
			}
			else {
				console.log("You have to be in the room before you delete a room");
			}
		}
	});

	socket.on("currentRoomInfo", function (data) { // process current room info and send it back to server side

		var currentRoom = userLocation[socket.currentUser];
		socket.currentRoom = currentRoom;
		socket.join(socket.currentRoom);

		var currentRoomUsers = allRoomUsers[socket.currentRoom];
		var currentRoomBanned = bannedUsers[socket.currentRoom];
		console.log(currentRoom);
		socket.emit("updateCurrentRoomInfo", { currentRoom: currentRoom, currentRoomUsers: currentRoomUsers, currentRoomBanned: currentRoomBanned });
	});

	socket.on("updateRoom", function () { // process update room info and send it back to server side
		io.sockets.emit("getRoomList", { rooms: allRooms });
	});

	socket.on("updateUser", function (data) { // process update user info and send it back to server side
		io.sockets.emit("getUserList", { users: allUsers, currentUser: socket.currentUser, newUser: data["newUser"] });
	});

	socket.on("enterRoom", function (data) { // process enter room info and send it back to server side
		var roomExist = false;
		var userInBannedList = false;
		var rmName = data["roomName"];
		for (var r in allRooms) {
			if (allRooms[r] == rmName) {
				roomExist = true;
			}
		}
		if (roomExist) {
			for (var u in bannedUsers[rmName]) {
				if (bannedUsers[rmName][u] == socket.currentUser) {
					userInBannedList = true;
				}
			}
			if (!userInBannedList) {
				var userInRoom = false;
				for (var u in allRoomUsers[rmName]) {
					if (allRoomUsers[rmName][u] == socket.currentUser) {
						userInRoom = true;
					}
				}
				if (!userInRoom) {
					var previousRoom = socket.currentRoom;
					allRoomUsers[previousRoom].splice(allRoomUsers[previousRoom].indexOf(socket.currentUser), 1);
					io.sockets.in(previousRoom).emit("joinRoom", { currentUser: socket.currentUser, currentRoom: previousRoom, currentRoomPW: null, currentUsers: allRoomUsers[previousRoom], joinRoom: false });
					socket.currentRoom = rmName;
					allRoomUsers[rmName].push(socket.currentUser);
					userLocation[socket.currentUser] = rmName;
					userPreviousRoom[socket.currentUser] = previousRoom;
					socket.join(rmName);
					io.sockets.in(rmName).emit("joinRoom", { currentUser: socket.currentUser, currentRoom: rmName, currentRoomPW: null, currentUsers: allRoomUsers[rmName], joinRoom: false });
					socket.emit("joinRoom", { currentUser: socket.currentUser, currentRoom: rmName, currentRoomPW: allRoomsPW[rmName], currentUsers: allRoomUsers[rmName], joinRoom: true });
				}
				else {
					console.log("You are already in the room!");
				}
			}
			else {
				console.log("You are banned from this room!");
			}
		}
		else {
			console.log("The room does not exist!");
		}
	});

	socket.on("kickUser", function (data) { // process kick user info and send it back to server side
		var username = data["username"];
		var userInRoom = false;
		socket.currentRoom = userLocation[socket.currentUser];
		if (socket.currentRoom == "lobby") {
			console.log("You cannot kick anyone out from the lobby!");
		}
		else {
			if (userLocation[username] != socket.currentRoom) {
				console.log("You cannot kick out someone who is not in the current room!");
			}
			else {
				if (socket.currentUser == roomOwner[socket.currentRoom]) {
					if (username == socket.currentUser) {
						console.log("You cannot kick yourself out of the room!");
					}
					else {
						userLocation[username] = "lobby";
						console.log("You have kicked " + username + " out of the room!");
						allRoomUsers[socket.currentRoom].splice(allRoomUsers[socket.currentRoom].indexOf(username), 1);
						allRoomUsers["lobby"].push(username);
						userPreviousRoom[username] = null;
						io.sockets.in(socket.currentRoom).emit("joinRoom", { currentUser: username, currentRoom: "lobby", currentRoomPW: null, currentUsers: allUsers, joinRoom: false });
						io.sockets.in(socket.currentRoom).emit("updateCurrentRoomInfo", { currentRoom: socket.currentRoom, currentRoomUsers: allRoomUsers[socket.currentRoom], currentRoomBanned: bannedUsers[socket.currentRoom] });
						io.sockets.in("lobby").emit("updateCurrentRoomInfo", { currentRoom: "lobby", currentRoomUsers: allRoomUsers["lobby"], currentRoomBanned: null });
						io.sockets.in(socket.currentRoom).emit("getKicked", { username: username, currentRoom: socket.currentRoom });
					}
				}
				else {
					console.log("You cannot kick anyone because you are not the owner of the room!");
				}
			}
		}
	});

	socket.on("banUser", function (data) { // process ban user info and send it back to server side
		var username = data["username"];
		var userInBannedList = false;
		var userInAllUsers = false;
		if (socket.currentRoom == "lobby") {
			console.log("You cannot bann user in the lobby!");
		}
		else {
			if (socket.currentUser == roomOwner[socket.currentRoom]) {
				if (username == socket.currentUser) {
					console.log("You cannot ban yourself!");
				}
				else {
					for (var u in bannedUsers[socket.currentRoom]) {
						if (bannedUsers[socket.currentRoom][u] == username) {
							userInBannedList = true;
						}
					}
					if (!userInBannedList) {
						for (var u in allUsers) {
							if (username == allUsers[u]) {
								userInAllUsers = true;
							}
						}
						if (userInAllUsers) {
							if (bannedUsers[socket.currentRoom] == null) {
								bannedUsers[socket.currentRoom] = [];
							}
							bannedUsers[socket.currentRoom].push(username);
							console.log("You have banned the user successfully!");
							io.sockets.in(socket.currentRoom).emit("getBannedUser", { currentUser: username, currentRoom: socket.currentRoom, banned: bannedUsers[socket.currentRoom] });
						}
						else {
							console.log("The User You Banned Does Not Exist!");
						}
					}
					else {
						console.log("The User is already in your banned list!");
					}
				}
			}
			else {
				console.log("You are not the owner of the room, so cannot ban anyone!");
			}
		}
	});

	socket.on("leaveRoom", function () { // process leave room info and send it back to server side
		var inRoom = false;
		if (userLocation[socket.currentUser] != "lobby") {
			inRoom = true;
		}
		if (inRoom) {
			var previousRoom = socket.currentRoom;
			allRoomUsers[previousRoom].splice(allRoomUsers[previousRoom].indexOf(socket.currentUser), 1);
			userLocation[socket.currentUser] = "lobby";
			socket.join("lobby");
			allRoomUsers["lobby"].push(socket.currentUser);
			console.log("You have left the room successfully!");
			io.sockets.in(socket.currentRoom).emit("leave", { currentRoom: socket.currentRoom, currentUser: socket.currentUser });
			socket.currentRoom = "lobby";
			io.sockets.in(previousRoom).emit("joinRoom", { currentUser: socket.currentUser, currentRoom: previousRoom, currentRoomPW: null, currentUserList: allRoomUsers[previousRoom], joinRoom: false });
			io.sockets.in("lobby").emit("joinRoom", { currentUser: socket.currentUser, currentRoom: "lobby", currentRoomPW: null, currentUsers: allRoomUsers[socket.currentRoom], joinRoom: false });
		}
		else {
			console.log("You are not in any room!");
		}
	});

	socket.on('message_to_server', function (data) { // process message and send it back to server side
		// This callback runs when the server receives a new message from the client.
		console.log("message: " + data["message"]); // log it to the Node.JS output
		socket.currentRoom = userLocation[socket.currentUser];
		io.sockets.in(socket.currentRoom).emit("message_to_client", { currentUser: socket.currentUser, message: data["message"] }) // broadcast the message to other users
	});

	socket.on('privateMessage_to_server', function (data) { // process private message and send it back to server side
		// This callback runs when the server receives a new message from the client.
		var sentTo = data["sentTo"];
		if (socket.currentUser == sentTo) {
			console.log("You cannot send messages to yourself!");
		}
		else {
			if (userLocation[sentTo] != userLocation[socket.currentUser]) {
				console.log("You cannot send messages to someone who is not in the same room with you!");
			}
			else {
				console.log("message: " + data["message"]); // log it to the Node.JS output
				socket.to(userSocketId[sentTo]).emit("privateMessage_to_client", { currentUser: socket.currentUser, sentTo: sentTo, message: data["message"] }) // broadcast the message to other users

			}
		}
	});
});
