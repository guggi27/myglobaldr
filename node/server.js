// server.js (Node.js WebRTC Signaling Server using Socket.io)

const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const app = express();
const server = http.createServer(app);
const io = new Server(server);

const rooms = {}; // { roomId: [socketId1, socketId2] }

io.on('connection', socket => {
  console.log('🔌 New connection:', socket.id);

  socket.on('create-room', roomId => {
    if (!rooms[roomId]) rooms[roomId] = [];
    if (rooms[roomId].length >= 2) {
      socket.emit('room-full');
      return;
    }

    rooms[roomId].push(socket.id);
    socket.join(roomId);
    console.log(`📞 ${socket.id} joined room ${roomId}`);

    if (rooms[roomId].length === 2) {
      // Notify the first user someone joined
      socket.to(roomId).emit('incoming-call');
    }
  });

  socket.on('offer', ({ roomId, offer }) => {
    socket.to(roomId).emit('offer', offer);
  });

  socket.on('answer', ({ roomId, answer }) => {
    socket.to(roomId).emit('answer', answer);
  });

  socket.on('ice-candidate', ({ roomId, candidate }) => {
    socket.to(roomId).emit('ice-candidate', candidate);
  });

  socket.on('reject-call', roomId => {
    socket.to(roomId).emit('call-rejected');
  });

  socket.on('disconnect', () => {
    for (const roomId in rooms) {
      rooms[roomId] = rooms[roomId].filter(id => id !== socket.id);
      if (rooms[roomId].length === 0) delete rooms[roomId];
    }
    console.log('❌ Disconnected:', socket.id);
  });
});

server.listen(3000, () => {
  console.log('🚀 Signaling server running on http://localhost:3000');
});
