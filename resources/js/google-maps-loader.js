let loadingPromise = null;

export async function loadGoogleMaps(apiKey) {
  if (loadingPromise) return loadingPromise;
  if (window.google?.maps) return window.google;

  loadingPromise = new Promise((resolve) => {
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&language=es&region=MX`;
    script.async = true;
    script.onload = () => { resolve(window.google); loadingPromise = null; };
    script.onerror = () => { loadingPromise = null; console.error('Google Maps failed to load'); };
    document.head.appendChild(script);
  });
  return loadingPromise;
}
