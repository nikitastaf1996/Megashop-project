function component_cart() {
    var component_inner = {};
    var cart_inner = function (event, props) {
        handleLinkEvents(event, props);
    }
    component_inner.handler = cart_inner;
    component_inner.propsFunction = function (props) {
        passPropsToChild('cart', 'cart_buttons', function () {
            props.main('/cart');
        })
    }
    return component_inner;
}
function component_cart_buttons() {
    var component_inner = {};
    component_inner.handler = function (event, props) {
        if (event.target.classList.contains('cart_block')) {

        };
        if (event.target.classList.contains('cart_add')) {
            cart_add(event, props);
        }
        if (event.target.classList.contains('cart_favorite')) {
            cart_favorite(event, props);
        }
        if (event.target.classList.contains('cart_remove')) {
            cart_remove(event, props);
        }
    };
    return component_inner;
}

function cart_add(event, props) {
    var parent = event.target.closest('[data-id]');
    var item_id = parent.getAttribute('data-id');
    var body = JSON.stringify({
        'item_id': item_id
    });
    parent.innerHTML = '<div class="cart_add items-center justify-center flex-1 h-full flex bg-violet-700"><i  class="fa-solid fa-clock cart_block"  style="color: #FFFFFF;"></i></div>';
    if (props.cart != undefined) {
        makeHttpRequest('/cart/addToCart', body, "POST", function (xhr) {
            props.cart();
        })
        return;
    }
    if (props.favorite != undefined) {
        makeHttpRequest('/cart/addToCart', body, "POST", function (xhr) {
            props.favorite();
        })
        return;
    }
    makeHttpRequest('/cart/addToCart', body, "POST", function (xhr) {
        updateRenderSurface(function (data) {
            var div = document.createElement('div');
            div.innerHTML = JSON.parse(data.response.response).content;
            data.container.replaceChildren(...div.firstChild.childNodes);
        }, data = {
            container: this,
            response: xhr
        });
    }.bind(event.currentTarget))
}


function cart_favorite(event, props) {
    var parent = event.target.closest('[data-id]');
    var item_id = parent.getAttribute('data-id');
    var body = JSON.stringify({
        'item_id': item_id
    });
    parent.innerHTML = '<div class="cart_add items-center justify-center flex-1 h-full flex bg-violet-700"><i  class="fa-solid fa-clock cart_block"  style="color: #FFFFFF;"></i></div>';
    if (props.favorite != undefined) {
        makeHttpRequest('/cart/updateFavorite', body, "POST", function (xhr) {
            props.favorite();
        })
    }
    else {
        makeHttpRequest('/cart/updateFavorite', body, "POST", function (xhr) {
            updateRenderSurface(function (data) {
                var div = document.createElement('div');
                div.innerHTML = JSON.parse(data.response.response).content;
                data.container.replaceChildren(...div.firstChild.childNodes);
            }, data = {
                container: this,
                response: xhr
            });
        }.bind(event.currentTarget))
    }
}

function cart_remove(event, props) {
    var parent = event.target.closest('[data-id]');
    var item_id = parent.getAttribute('data-id');
    var body = JSON.stringify({
        'item_id': item_id
    });
    parent.innerHTML = '<div class="cart_add items-center justify-center flex-1 h-full flex bg-violet-700"><i  class="fa-solid fa-clock cart_block"  style="color: #FFFFFF;"></i></div>';
    if (props.cart != undefined) {
        makeHttpRequest('/cart/removeFromCart', body, "POST", function (xhr) {
            props.cart();
        })
    }
    else {
        makeHttpRequest('/cart/removeFromCart', body, 'POST', function (xhr) {
            updateRenderSurface(function (data) {
                var div = document.createElement('div');
                div.innerHTML = JSON.parse(data.response.response).content;
                data.container.replaceChildren(...div.firstChild.childNodes);
            }, data = {
                container: this,
                response: xhr
            });
        }.bind(event.currentTarget));
    }


}