/* Legacy compatibility shim.
 * Some pages reference /js/jquery.js (old template path). Redirect to the real Polo asset.
 */
(function () {
  if (window.jQuery) return;
  document.write('<script src="/assets/polo/js/jquery.js"><\\/script>');
})();

