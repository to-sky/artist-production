var buttons = document.querySelectorAll('.link_dropdown_trigger');

function findParent(e, className) {
  var head = e;

  do {
    if (head.classList && head.classList.contains(className)) {
      return head;
    }
  } while(head = head.parentNode);

  return null;
}

buttons && buttons.forEach(function (b) {

  var wrap = findParent(b, 'link_dropdown_wrap');

  b.addEventListener('click', function (e) {
    e.preventDefault();

    wrap && wrap.classList.toggle('active');

    return false;
  });

});