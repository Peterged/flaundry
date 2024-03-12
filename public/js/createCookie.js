function createCookie(name, value, days) {
  let expires;

  if (days) {
    let date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    expires = "; expires=" + date.toGMTString();
  } else {
    expires = "";
  }

  document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
}


document.addEventListener("DOMContentLoaded", () => {
    createCookie("clientTimezone", Intl.DateTimeFormat().resolvedOptions().timeZone, 30);
});