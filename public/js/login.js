function component_login(){
    var component_inner = {};
    component_inner.handler = component_login_inner;
    return component_inner;
}

function component_login_inner(event,props){
    handleLinkEvents(event,props);
    if (event.target.classList.contains('login')) {
        var email = document.querySelector('input[placeholder="Email"]').value;
        var password = document.querySelector('input[placeholder="Пароль"]').value;
        var data = JSON.stringify({
            'email':email,
            "password":password
        });
        makeHttpRequest('/login',data,"POST",function(xhr){
            if(xhr.status === 422){
                var message = document.querySelector('.login-message');
                message.classList.remove('hidden');
                message.innerHTML = JSON.parse(xhr.response).message;
            }
        })
    }   
}
function component_register(){
    var component_inner = {};
    component_inner.handler = component_register_inner;
    return component_inner;
}

function component_register_inner(event,props){
    handleLinkEvents(event,props);
    if (event.target.classList.contains('register')) {
        var name = document.querySelector('input[placeholder="Имя"]').value;
        var email = document.querySelector('input[placeholder="Email"]').value;
        var password = document.querySelector('input[placeholder="Пароль"]').value;
        var confirmpassword = document.querySelector('input[placeholder="Подтвердите пароль"]').value;
        var data = JSON.stringify({
            'name':name,
            'email':email,
            "password":password,
            'password_confirmation':confirmpassword
        });
        makeHttpRequest('/register',data,"POST",function(xhr){
            if(xhr.status === 422){
                var message = document.querySelector('.register-message');
                message.classList.remove('hidden');
                message.innerHTML = JSON.parse(xhr.response).message;
            }
        })
    }   
}

function component_passwordreset(){
    var component_inner = {};
    component_inner.handler = component_passwordreset_inner;
    return component_inner;
}

function component_passwordreset_inner(event,props){
    handleLinkEvents(event,props);
    if (event.target.classList.contains('password-reset')) {
        var email = document.querySelector('input[placeholder="Email"]').value;
        var data = JSON.stringify({
            'email':email
        });
        makeHttpRequest('/forgot-password',data,"POST",function(xhr){
            if(xhr.status === 422){
                var message = document.querySelector('.password-reset-message');
                message.classList.remove('hidden');
                message.innerHTML = JSON.parse(xhr.response).message;
            }
        })
    }   
}