/* Legacy compatibility shim.
 * Some pages reference /js/plugins.js (old template path). Redirect to the real Polo asset.
 */
(function () {
  // Ensure jQuery is present before plugins load.
  if (!window.jQuery) {
    document.write('<script src="/assets/polo/js/jquery.js"><\\/script>');
  }
  document.write('<script src="/assets/polo/js/plugins.js"><\\/script>');
})();

