/* Legacy compatibility shim.
 * Some pages reference /js/functions.js (old template path). Redirect to the real Polo asset.
 */
(function () {
  // Ensure dependencies exist in legacy include order.
  if (!window.jQuery) {
    document.write('<script src="/assets/polo/js/jquery.js"><\\/script>');
  }
  document.write('<script src="/assets/polo/js/plugins.js"><\\/script>');
  document.write('<script src="/assets/polo/js/functions.js"><\\/script>');
})();

