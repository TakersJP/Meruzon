const express = require('express');
const session = require('express-session');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.urlencoded({ extended: true }));
app.use(session({ secret: 'secret', resave: true, saveUninitialized: true }));

const users = [
    { userid: 'user1', password: 'password1' },
    { userid: 'user2', password: 'password2' }
];

app.post('/login', (req, res) => {
    const username = req.body.username;
    const password = req.body.password;

    if (!username || !password) {
        req.session.loginMessage = 'Username and password are required';
        return res.redirect('/login');
    }

    const user = users.find(u => u.userid === username && u.password === password);

    if (user) {
        req.session.authenticatedUser = username;
        req.session.loginMessage = null;
        return res.redirect('/index');
    } else {
        req.session.loginMessage = 'Invalid username or password';
        return res.redirect('/login');
    }
});

app.get('/login', (req, res) => {
    res.sendFile(__dirname + '/login.html');
});

app.get('/index', (req, res) => {
    if (req.session.authenticatedUser) {
        res.sendFile(__dirname + '/index.html');
    } else {
        res.redirect('/login');
    }
});

app.listen(3000, () => {
    console.log('Server started on http://localhost:3000');
});
