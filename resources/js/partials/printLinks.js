var printLinks = document.querySelectorAll('a.print_link');

printLinks && printLinks.forEach(function(el) {
  el.addEventListener('click', function (e) {
    e.preventDefault();

    window.open(el.href, 'Document print', 'width=708px');

    return false;
  });
});