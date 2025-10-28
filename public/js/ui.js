function component_form() {
  var component_inner = {};
  var f = component_form_inner;
  component_inner.handler = f;
  return component_inner;
}
function component_form_inner(event, props) {
  handleLinkEvents(event, props);
  var form = event.currentTarget.closest('[data-component]');
  if (event.target.classList.contains('action')) {
    var path = form.getAttribute('data-path');
    var handler = form.getAttribute('data-handler');
    var elements = form.elements;
    var data = {};
    for (let index = 0; index < elements.length; index++) {
      const element = elements[index];
      data[element.name] = element.value;
    }
    data = JSON.stringify(data);
    var handlerFunction = window['form_handler_' + handler].bind(form);
    makeHttpRequest(path, data, 'POST', handlerFunction);
  }

}

function form_handler_message(xhr) {
  var message = JSON.parse(xhr.response).message;
  this.querySelector('.form-message').classList.remove('hidden');
  this.querySelector('.form-message').innerHTML = message;
}


function component_search() {
  var component_inner = {};
  var f = component_search_inner();
  component_inner.handler = f;
  return component_inner;
}

function component_search_inner(event, props) {
  var previousEventDate = 0;
  var timeout = null;
  var opened = false;
  return function innerFunction(event, props) {
    if (event.target.parentElement.hasAttribute('href')) {
      page = event.target.parentElement.getAttribute('href');
      document.querySelector('.search-results').classList.add('hidden');
      document.querySelector('[data-component="search"]').classList.add('relative');
      document.querySelector('[data-component="search"]').classList.remove('absolute');
      document.querySelector('.search-back').classList.add('hidden');
      props.main(page);
    }
    if (event.target.classList.contains('search-action')) {
      var search_input = document.querySelector('.search-input');
      var search_query = search_input.value;
      if (search_input.value.length > 0) {
        var page = "search.php?query=".concat(encodeURIComponent(search_input.value));
        props.main(page);
      }
    }
    //mobile behaviour
    if (window.matchMedia('(max-width: 640px)').matches) {
      if (event.target.nodeName == 'INPUT'
        && opened == false
        && event.type == "click"
      ) {
        document.querySelector('.search-results').classList.remove('hidden');
        document.querySelector('[data-component="search"]').classList.replace('relative', 'absolute');
        document.querySelector('.search-back').classList.remove('hidden');
        opened = true
      }
      if (event.target.classList.contains('search-back')) {
        document.querySelector('.search-results').classList.add('hidden');
        document.querySelector('[data-component="search"]').classList.add('relative');
        document.querySelector('[data-component="search"]').classList.remove('absolute');
        document.querySelector('.search-back').classList.add('hidden');
        opened = false
      }
      if (event.type == 'input') {
        if (previousEventDate == 0) {
          previousEventDate = Date.now();
          timeout = setTimeout(function () {
            var search_input = document.querySelector('.search-input');
            var search_query = search_input.value;
            if (search_query.length != 0) {
              var body = JSON.stringify({
                'search_query': search_query,
              });
              makeHttpRequest('/search', body, 'POST', function (xhr) {
                if (JSON.parse(xhr.response).content.length > 0) {
                  var search_results = document.querySelector('.search-results');
                  search_results.classList.remove('hidden');
                  search_results.innerHTML = JSON.parse(xhr.response).content;
                }
                if (JSON.parse(xhr.response).content.length == 0) {
                  var search_results = document.querySelector('.search-results');
                  search_results.innerHTML = '';
                }
              });
            }
            else {
              var search_results = document.querySelector('.search-results');
              search_results.innerHTML = '';
            }
          }, 100);
        }
        if (previousEventDate != 0 && Date.now() - previousEventDate > 100) {
          clearTimeout(timeout);
          previousEventDate = 0;
          var search_input = document.querySelector('.search-input');
          var search_query = search_input.value;
          if (search_query.length != 0) {
            var body = JSON.stringify({
              'search_query': search_query,
            });
            makeHttpRequest('/search', body,"POST", function (xhr) {
              if (JSON.parse(xhr.response).content.length > 0) {
                var search_results = document.querySelector('.search-results');
                search_results.classList.remove('hidden');
                search_results.innerHTML = JSON.parse(xhr.response).content;
              }
              if (JSON.parse(xhr.response).length == 0) {
                var search_results = document.querySelector('.search-results');
                search_results.innerHTML = '';
              }
            });
          }
          else {
            var search_results = document.querySelector('.search-results');
            search_results.innerHTML = '';
          }
        }
      }
    }
    //desktop behaviour
    if (!window.matchMedia('(max-width: 640px)').matches) {
      if (event.target.nodeName == 'INPUT'
        && document.querySelector('.search-input').value.length != 0
        && event.type == "click"
      ) {
        var search_results = document.querySelector('.search-results');
        search_results.classList.remove('hidden');
      }
      if (event.type == 'input') {
        if (previousEventDate == 0) {
          previousEventDate = Date.now();
          timeout = setTimeout(function () {
            var search_input = document.querySelector('.search-input');
            var search_query = search_input.value;
            if (search_query.length != 0) {
              var body = JSON.stringify({
                'search_query': search_query,
              });
              makeHttpRequest('/search', body,"POST", function (xhr) {
                if (JSON.parse(xhr.response).content.length > 0) {
                  var search_results = document.querySelector('.search-results');
                  search_results.classList.remove('hidden');
                  search_results.innerHTML = JSON.parse(xhr.response).content;
                }
                if (JSON.parse(xhr.response).content.length == 0) {
                  var search_results = document.querySelector('.search-results');
                  search_results.classList.add('hidden');
                }
              });
            }
            else {
              var search_results = document.querySelector('.search-results');
              search_results.classList.add('hidden');
            }
          }, 100);
        }
        if (previousEventDate != 0 && Date.now() - previousEventDate > 100) {
          clearTimeout(timeout);
          previousEventDate = 0;
          var search_input = document.querySelector('.search-input');
          var search_query = search_input.value;
          if (search_query.length != 0) {
            var body = JSON.stringify({
              'search_query': search_query,
            });
            makeHttpRequest('/search', body,"POST", function (xhr) {
              if (JSON.parse(xhr.response).content.length > 0) {
                var search_results = document.querySelector('.search-results');
                search_results.classList.remove('hidden');
                search_results.innerHTML = JSON.parse(xhr.response).content;
              }
              if (JSON.parse(xhr.response).content.length == 0) {
                var search_results = document.querySelector('.search-results');
                search_results.classList.add('hidden');
              }
            });
          }
          else {
            var search_results = document.querySelector('.search-results');
            search_results.classList.add('hidden');
          }
        }
      }
    }
  }
}

function changePage(page) {
  makeHttpRequest(page, [], 'GET', function (xhr) {
    updateRenderSurface(function (data) {
      var renderSurface = document.querySelector('.render-surface');
      renderSurface.innerHTML = JSON.parse(data.response).content;
      var title = document.querySelector('title');
      title.innerHTML = JSON.parse(data.response).title;
      scrollIfNeeded(page);
    }, xhr);
  })

}
function component_slider() {
  var component_inner = {};
  component_inner.handler = component_slider_inner;
  return component_inner;
}
function component_slider_inner(event, props) {
  var mainElement = event.target;
  while (mainElement.getAttribute('data-slider-type') == null) {
    mainElement = mainElement.parentNode;
  }
  var sliderType = mainElement.getAttribute('data-slider-type');
  var mainImages = Array.from(mainElement.querySelector('.slider-main-elements').children);
  var controls = Array.from(mainElement.querySelector('.slider-controls').children);
  switch (sliderType) {
    case 'sides':
      if (event.target.hasAttribute('href') || event.target.parentElement.hasAttribute('href')) {
        if (event.target.hasAttribute('href')) {
          page = event.target.getAttribute('href');
        }
        if (event.target.parentElement.hasAttribute('href')) {
          page = event.target.parentElement.getAttribute('href');
        }
        props.main(page);
      }
      var imageCount = mainImages.length;
      for (var i = 0; i < imageCount; i++) {
        if (mainImages[i].classList.contains('block')) {
          var activeImage = i;
          break;
        }
      }
      mainImages.forEach(function (element) {
        element.classList.remove('block');
        element.classList.add('hidden');
      })
      if (event.target.classList.contains('forward-button')) {
        if (activeImage + 1 > imageCount - 1) {
          activeImage = 0;
        }
        else {
          activeImage = activeImage + 1;
        }
      }
      if (event.target.classList.contains('backward-button')) {
        if (activeImage - 1 < 0) {
          activeImage = imageCount - 1;
        }
        else {
          activeImage = activeImage - 1;
        }
      }
      mainImages[activeImage].classList.remove('hidden');
      mainImages[activeImage].classList.add('block');
      break;
    case 'switcher':
      if (!mainElement.querySelector('.slider-main-elements').contains(event.target)) {
        mainImages.forEach(function (element) {
          element.classList.remove('block');
          element.classList.add('hidden');
        });
        controls.forEach(function (element) {
          element.classList.remove('border-[#fc0]');
          element.classList.remove('border-4');
          element.classList.remove('border-solid');
        });
        var imageToShow = event.target.getAttribute('data-id');
        mainImages[imageToShow].classList.remove('hidden');
        mainImages[imageToShow].classList.add('block');
        controls[imageToShow].classList.add('border-[#fc0]');
        controls[imageToShow].classList.add('border-4');
        controls[imageToShow].classList.add('border-solid');
      }

      break;
  }


}

function component_category() {
  var component_inner = {};
  component_inner.handler = component_category_inner();
  return component_inner;

}

function component_category_inner(event, props) {
  var level = 0;

  return function innerFunction(event, props) {

    function resetCategoriesVisibilityMobile() {
      var category_list = document.querySelector('.category_list');
      var categories = Array.of(category_list.querySelectorAll('[data-level]'));
      categories[0].forEach(function (category) {
        category.classList.add('hidden');
      });
      var parentCategories = Array.of(category_list.querySelectorAll('[data-level="0"]'));
      parentCategories[0].forEach(function (category) {
        category.classList.remove('hidden');
      });
    }

    if (event.target.classList.contains('categories_open')) {
      event.currentTarget.classList.remove('categories_open');
      event.currentTarget.classList.add('categories_close');
      document.querySelectorAll('.categories_close')[1].classList.remove('!hidden');
      document.querySelectorAll('.categories_open')[0].classList.add('!hidden');
      document.querySelector('.category_list').classList.remove('hidden');
    }
    else if (event.target.classList.contains('categories_close')) {
      if (window.matchMedia('(max-width: 640px)').matches == true) {
        resetCategoriesVisibilityMobile();
      }
      event.currentTarget.classList.remove('categories_close');
      event.currentTarget.classList.add('categories_open');
      document.querySelectorAll('.categories_close')[0].classList.add('!hidden');
      document.querySelectorAll('.categories_open')[1].classList.remove('!hidden');
      document.querySelector('.category_list').classList.add('hidden');
    }
    if (event.target.classList.contains('categories_back') && level == 1) {
      resetCategoriesVisibilityMobile();
      level = 0;
    }
    if (event.target.getAttribute('data-level') !== null
      && window.matchMedia('(max-width: 640px)').matches
      && level == 1) {
      page = event.target.getAttribute('href');
      props.main(page);
      resetCategoriesVisibilityMobile();
      event.currentTarget.classList.remove('categories_close');
      event.currentTarget.classList.add('categories_open');
      document.querySelectorAll('.categories_close')[0].classList.add('!hidden');
      document.querySelectorAll('.categories_open')[1].classList.remove('!hidden');
      document.querySelector('.category_list').classList.add('hidden');
    }
    if (event.target.getAttribute('data-level') != null
      && window.matchMedia('(max-width: 640px)').matches == true
      && level == 0) {
      var category_list = document.querySelector('.category_list');
      var categories = Array.of(category_list.querySelectorAll('[data-level]'));
      categories[0].forEach(function (category) {
        category.classList.add('hidden');
      });
      var categoryId = event.target.getAttribute('data-parent-id');
      var selector = '[data-parent-id="' + categoryId + '"]';
      var categories = Array.of(category_list.querySelectorAll(selector));
      categories[0].forEach(function (category) {
        category.classList.remove('hidden');
      });
      level = 1;
    }
    if (event.target.getAttribute('data-level') !== null
      && !window.matchMedia('(max-width: 640px)').matches) {
      page = event.target.getAttribute('href');
      props.main(page);
      event.currentTarget.classList.remove('categories_close');
      event.currentTarget.classList.add('categories_open');
      document.querySelectorAll('.categories_close')[0].classList.add('!hidden');
      document.querySelectorAll('.categories_open')[1].classList.remove('!hidden');
      document.querySelector('.category_list').classList.add('hidden');
    }







  }
}
