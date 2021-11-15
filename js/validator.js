// ##############################
function validate() {
  console.log("validate");
  var elements_to_validate = all("[data-validate]");

  elements_to_validate.forEach(function (element) {
    element.classList.remove("is-invalid");
  });
  elements_to_validate.forEach(function (element) {
    switch (element.getAttribute("data-validate")) {
      case "str": //if string, set min and max characters to the data-attributes values
        if (
          element.value.length < parseInt(element.getAttribute("data-min")) ||
          element.value.length > parseInt(element.getAttribute("data-max"))
        ) {
          //put class on parentNode, so we can style sibling as well
          element.parentNode.classList.add("is-invalid");
          return false;
        }
        break;
      case "int": //if integers, set min and max integers to the data-attributes values
        if (
          !parseInt(element.value) ||
          parseInt(element.value) < parseInt(element.getAttribute("data-min")) ||
          parseInt(element.value) > parseInt(element.getAttribute("data-max"))
        ) {
          element.parentNode.classList.add("is-invalid");
        }
        break;
      case "email": //if email, make sure it passes regex pattern for email
        const re =
          /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(element.value.toLowerCase())) {
          element.parentNode.classList.add("is-invalid");
        }
        break;
      case "match": //if password make sure it passes regex pattern for password and that the password and match password is equal
        const pattern =
          /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\+|\=|\[|\{|\]|\}|\||\\|\'|\<|\,|\.|\>|\?|\/|\""|\;|\:|\s]).{8,32}$/;
        if (!pattern.test(element.value)) {
          element.parentNode.classList.add("is-invalid");
          one(".password .invalid-feedback").textContent =
            "You need at least 8 characters, 1 uppercase, 1 lowercase, 1 digit and max 50 characters";
        }
        if (one(`[name='${element.getAttribute("data-match-name")}']`)) {
          if (element.value != one(`[name='${element.getAttribute("data-match-name")}']`).value) {
            element.parentNode.classList.add("is-invalid");
            one(".password .invalid-feedback").textContent = "The passwords doesn't match.";
          }
        }
        break;
      case "url": //if url, make sure it passes regex pattern for url
        const link_pattern = /^(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/i;
        if (!link_pattern.test(element.value.toLowerCase()) && element.value !== '') {
          console.log('link2')
          element.parentNode.classList.add("is-invalid");        }
        break;
       case "file": //if file, make sure a file is chosen
        if (element.value == "") {
          element.classList.add("is-invalid");
        }
        break; 
      case "admin_key": //if admin-key (signup), make sure it matches the below string (just to make signup easy in development / test mode)
        const admin = document.querySelector("#admin");
        if (element.value != 'cQxIQtxEycwGq1RWzpcZUQ@' && admin.checked) {
          element.classList.add("is-invalid");
        }
        break;
    }
  });

  return one(".is-invalid", event.target) ? false : true;
}

// ##############################
function clear_parent_error() {
  event.target.parentNode.classList.remove("is-invalid");
  console.log("clear error");
}
function clear_sibling_error() {
  document.querySelector(".password > label").classList.remove("is-invalid");
  console.log("clear error");
}

function clear_element_error() {
  event.target.classList.remove("is-invalid");
  console.log("clear error");
}

// ##############################
function one(q, from = document) {
  return from.querySelector(q);
}
function all(q, from = document) {
  return from.querySelectorAll(q);
}
