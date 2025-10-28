function makeHttpRequest(path, body,httpmethod, callback) {
  var xhr = new XMLHttpRequest();
  xhr.open(httpmethod, path);
  xhr.setRequestHeader('Content-Type', 'application/json');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onload = function () {
    if (xhr.status === 200 || xhr.status === 422) {
      if(JSON.parse(xhr.response).redirect){
        changePage(JSON.parse(xhr.response).redirect);
        return;
      }
        callback(xhr);
    } else {
      console.log('Request failed.  Returned status of ' + xhr.status);
    }
  };
  xhr.send(body);
}

function scrollIfNeeded(url){
  try {
    var uri = new URL(url);
    if (uri.hash !== '') {
      var elementToScrollTo = document.getElementById(uri.hash.replace('#', ''));
      elementToScrollTo.scrollIntoView();
    }
    else {
      var elementToScrollTo = document.querySelector('body');
      elementToScrollTo.scrollIntoView();
    }
  } catch (error) {

  }
}

function updateRenderSurface(container_update_function,data) {
  container_update_function(data);
  updateEventHandlers();

};

function handleLinkEvents(event,props){
  if (event.target.hasAttribute('href') || event.target.parentElement.hasAttribute('href')) {
    props.main(event.target.closest('[href]'));
  }
}
function handleEvents(event) {
  event.preventDefault();
  if (event.currentTarget.getAttribute('data-component') != null) {
    event.stopPropagation();
  }
  try {
    var componentName = event.currentTarget.getAttribute('data-component');
    var componentHandler = window.componentInformation[componentName].handler;
    if (window.componentInformation[componentName].props !== undefined) {
      componentHandler(event, window.componentInformation[componentName].props);
    }
    else {
      componentHandler(event, {});
    }

  } catch (error) {

  }
}

function passPropsToChild(parent, child, props) {
  if (window.componentInformation[child] !== undefined) {
    props = {
      [parent]: props
    }
    window.componentInformation[child]['props'] = props;
  }


}

function updateEventHandlers() {
  window.componentInformation = {};
  var components = document.querySelectorAll('[data-component]');
  var element = document.querySelector('body');
  var tree = {};
  function treeWalk(pathInTree, element) {
    if (element.getAttribute('data-component') !== null) {
      if (pathInTree == null) {
        pathInTree = element.getAttribute('data-component');
        tree[pathInTree] = {};
      }
      else {
        if (tree[pathInTree][element.getAttribute('data-component')] == undefined) {
          tree[pathInTree][element.getAttribute('data-component')] = element.getAttribute('data-component');
        }
      }

    }
    for (let index = 0; index < element.children.length; index++) {
      treeWalk(pathInTree, element.children[index]);
    }
  }
  treeWalk(null, element);


  components.forEach(function (component) {
    ['click', 'input'].forEach(function (e) {
      var componentName = component.getAttribute('data-component');
      window.componentInformation[component.getAttribute('data-component')] = window['component_'.concat(component.getAttribute('data-component'))]();
      component.addEventListener(e, handleEvents);
    });
  });

  function passProps(tree) {
    for (let index = 0; index < Object.keys(tree).length; index++) {
      if (window.componentInformation[Object.keys(tree)[index]].propsFunction !== undefined) {
        if (window.componentInformation[Object.keys(tree)[index]].props !== undefined) {
          window.componentInformation[Object.keys(tree)[index]].propsFunction(window.componentInformation[Object.keys(tree)[index]].props);
        }
        else {
          window.componentInformation[Object.keys(tree)[index]].propsFunction();
        }

      }
      if (typeof tree[Object.keys(tree)[index]] !== 'string') {
        passProps(tree[Object.keys(tree)[index]]);
      }
    }

  }
  passProps(tree);
}


window.addEventListener('load', updateEventHandlers);