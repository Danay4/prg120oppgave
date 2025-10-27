/* funksjoner.js */
function bekreft() {
  return confirm("Er du sikker ?");
}

// Simple success/info popup used after saving
function visBekreftelse(melding) {
  alert(melding);
}

// Inject a friendly success banner if a query param (default 'ok') is present
function visSuksessFraQuery(melding, param) {
  try {
    param = param || 'ok';
    var usp = new URLSearchParams(window.location.search);
    if (!usp.has(param)) return;

    var p = document.createElement('p');
    p.textContent = melding || 'Lagret!';
    p.style.cssText = 'background:#eaffea;border:1px solid #b6e3b6;padding:.5rem .75rem;border-radius:.25rem;margin:.5rem 0;';
    // Try to insert near the top, before the first H1 if possible
    var h1 = document.querySelector('h1');
    if (h1 && h1.parentNode) {
      h1.parentNode.insertBefore(p, h1.nextSibling);
    } else {
      document.body.insertBefore(p, document.body.firstChild);
    }

    // Clean the URL so ?ok=1 disappears
    if (history && history.replaceState) {
      usp.delete(param);
      var qs = usp.toString();
      var newUrl = window.location.pathname + (qs ? '?' + qs : '');
      history.replaceState({}, '', newUrl);
    }
  } catch (e) {
    // As a fallback, show an alert
    try { alert(melding || 'Lagret!'); } catch (_) {}
  }
}
