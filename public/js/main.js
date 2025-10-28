window.addEventListener('popstate', component_main_inner);
function component_main() {
        var component_inner = {};
        component_inner.handler = component_main_inner;
        component_inner.propsFunction = function () {
                passPropsToChild('main', 'slider', component_main_child_function);
                passPropsToChild('main', 'category', component_main_child_function);
                passPropsToChild('main', 'search', component_main_child_function);
                passPropsToChild('main', 'cart', component_main_child_function);
                passPropsToChild('main', 'form', component_main_child_function);
                passPropsToChild('main', 'favorite', component_main_child_function);
        }
        return component_inner;
}
function component_favorite() {
        var component_inner = {};
        var favorite_inner = function (event, props) {
                handleLinkEvents(event, props);
        }
        component_inner.handler = favorite_inner;
        component_inner.propsFunction = function (props) {
                passPropsToChild('favorite', 'cart_buttons', function () {
                        props.main('/favorite');
                })
        }
        return component_inner;
}
function component_main_inner(event) {
        // console.log(event);
        if (event.type == 'popstate') {
                var page = window.location.href;
        }
        else {
                var page = event.target.closest('a[href]');
                page = page.getAttribute('href');
        }
        var search_results = document.querySelector('.search-results');
        search_results.classList.add('hidden');


        component_main_child_function(page);
}

function component_main_child_function(page) {
        var search_results = document.querySelector('.search-results');
        search_results.classList.add('hidden');
        if (!page) return;
        history.pushState({}, "", page);
        changePage(page);
}