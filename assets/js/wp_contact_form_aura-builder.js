jQuery(document).ready(function ($) {

  $(".list").on({
    dragstart: function (event) {
      event.originalEvent.dataTransfer.setData("text/plain", event.target.id);
    },
    dragend: function () {
      $(".contentholder").removeClass("over");
    }
  });

  $("#dropzone").on({
    dragenter: function (event) {
      event.preventDefault();
    },
    dragover: function (event) {
      event.preventDefault();
      $(this).addClass("over");
    },
    dragleave: function () {
      $(".contentholder").removeClass("over");
    },
    drop: function (event) {
      event.preventDefault();
      const id = event.originalEvent.dataTransfer.getData("text/plain");
      const uniqueKey = "fieldid" + Math.floor(Math.random() * 100000000);

      const $clone = $("#" + id)
        .clone()
        .removeAttr("id")
        .addClass("dropped")
        .attr("data-key", uniqueKey)
        .attr("draggable", true);

      const $buttons = $(`
        <div class="box-buttons">
          <button class="reorder-btn">Reorder</button>
          <button class="edit-btn">Edit</button>
        </div>
      `);

      const $keyLabel = $(`<div class="key-label">${uniqueKey}</div>`);
      $clone.append($buttons).append($keyLabel);

      const fieldType = $clone.data("type");
      let fieldHTML = "";

      switch (fieldType) {
        case "inputText":
          fieldHTML = `<label for="${uniqueKey}">Text Field</label><input type="text" id="${uniqueKey}" name="${uniqueKey}" placeholder="Enter text">`;
          break;
        case "email":
          fieldHTML = `<label for="${uniqueKey}">Email</label><input type="email" id="${uniqueKey}" name="${uniqueKey}" placeholder="Enter email">`;
          break;
        case "textarea":
          fieldHTML = `<label for="${uniqueKey}">Message</label><textarea id="${uniqueKey}" name="${uniqueKey}" rows="4" placeholder="Write here..."></textarea>`;
          break;
        case "select":
          fieldHTML = `<label for="${uniqueKey}">Select Option</label><select id="${uniqueKey}" name="${uniqueKey}"><option>Option 1</option><option>Option 2</option></select>`;
          break;
        case "radio":
          fieldHTML = `<label>Choose One</label>
            <div class="radiolists">
              <label><input type="radio" name="${uniqueKey}"> Option 1</label>
              <label><input type="radio" name="${uniqueKey}"> Option 2</label>
            </div>`;
          break;
        case "checkbox":
          fieldHTML = `<label>Select Items</label>
            <div class="checkList">
              <label><input type="checkbox" name="${uniqueKey}[]"> Item 1</label>
              <label><input type="checkbox" name="${uniqueKey}[]"> Item 2</label>
            </div>`;
          break;
        case "number":
          fieldHTML = `<label for="${uniqueKey}">Number</label><input type="number" id="${uniqueKey}" name="${uniqueKey}" placeholder="Enter number">`;
          break;
        case "date":
          fieldHTML = `<label for="${uniqueKey}">Date</label><input type="date" id="${uniqueKey}" name="${uniqueKey}">`;
          break;
        case "phone":
          fieldHTML = `<label for="${uniqueKey}">Phone</label><input type="tel" id="${uniqueKey}" name="${uniqueKey}" placeholder="Enter phone number">`;
          break;
        case "url":
          fieldHTML = `<label for="${uniqueKey}">Website</label><input type="url" id="${uniqueKey}" name="${uniqueKey}" placeholder="https://example.com">`;
          break;
        default:
          fieldHTML = `<input type="text" id="${uniqueKey}" name="${uniqueKey}" placeholder="Default field">`;
      }

      $clone.append($(fieldHTML));
      $(this).append($clone);
    }
  });

  $(document).on("click", ".edit-btn", function () {
    const $field = $(this).closest(".dropped");
    const key = $field.attr("data-key");
    const type = $field.data("type");

    const $modal = $(".wp_contact_form_aura_modal");
    $modal.fadeIn(200).attr("data-editing", key).attr("data-type", type);
  });

  $(document).on("click", "#save_field_changes", function () {
    $(".wp_contact_form_aura_modal").fadeOut(200);
  });

  $(document).on(
    "click",
    "#close_modal, .wp_contact_form_aura_modal_overlay",
    function () {
      $(".wp_contact_form_aura_modal")
        .fadeOut(200)
        .removeAttr("data-editing");
    }
  );

});