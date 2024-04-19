import React, { useState } from 'react'

import {Form, Button} from 'react-bootstrap'
import { Link, useNavigate } from 'react-router-dom';

import {Text} from '../contexts/languageContext'

import axios from 'axios'

import '../styles/Form.css'


function Register() {
    const navigate = useNavigate();

    const[username, setUserName] = useState('');
    const[password, setPassword] = useState('');

    const[usernameInValid, setUsernameInValid] = useState(false);
    const[passwordInValid, setPasswordInValid] = useState(false);

    const[usernameTaken, setUsernameTaken] = useState(false);
    const[oldUsername, setOldUsername] = useState('');

    const handleRegistration = e => {
        e.preventDefault();

        setUsernameInValid(false);
        setPasswordInValid(false);
        setUsernameTaken(false);

        if (username === '' || password === '' || oldUsername === username) {
            if(username === ''){
                setUsernameInValid(true);
            } else if(oldUsername.includes(username)){
                navigate("/login", {replace: true});
            }

            if(password === ''){
                setPasswordInValid(true);
            }

            e.stopPropagation();
        } else {
            const data = {
                username: username,
                password: password
            }

            axios.post('http://localhost:8000/auth/register2.php', JSON.stringify(data))
            .then(response => {
                navigate("/login")
            })
            .catch(error => {
                if (error.response && error.response.status === 409) {
                    setUsernameInValid(true);
                    setUsernameTaken(true);
                    setOldUsername(username);
                    console.log('Помилка 409 Conflict:', error.response.data);
                } else if (error.response && error.response.status === 500){
                    navigate("/500")
                }
            });
        }
    }

  return (
    <div className='p-xxl-5 p-xl-5 p-lg-5 p-md-3 d-flex justify-content-center'>
        <Form className='border p-4 form' onSubmit={e => handleRegistration(e)} noValidate>
            <h3><Text id="register" /></h3>
            <p>
                <Link to="/login"><Text id="DoYouHaveAccount" />?</Link>
            </p>
            <hr></hr>
            <Form.Group className='mb-3'>
                <Form.Label><Text id="username" /></Form.Label>
                <Form.Control
                    id='usernameField'
                    type="text"
                    placeholder="username"
                    value={username}
                    onChange={e => setUserName(e.target.value)}
                    isInvalid={usernameInValid}
                    noValidate
                    required
                />
                <Form.Control.Feedback type="invalid">
                    {usernameTaken ? <Text id="usernameTaken" /> : <Text id="pleaseInputUsername" />}
                </Form.Control.Feedback>
            </Form.Group>
            <Form.Group className='mb-3'>
                <Form.Label><Text id="password" /></Form.Label>
                <Form.Control
                    id='passwordField'
                    type="password"
                    placeholder="password"
                    value={password}
                    onChange={e => setPassword(e.target.value)}
                    isInvalid={passwordInValid}
                    noValidate
                    required
                />
                <Form.Control.Feedback type="invalid"><Text id="pleaseInputPassword" /></Form.Control.Feedback>
            </Form.Group>
            <Button type="submit" value="Submit"><Text id="register" /></Button>
        </Form>
    </div>
  )
}

export default Register