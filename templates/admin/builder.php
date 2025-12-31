<div class="container">
    <div class="d-flex formholder">
        <div class="droplist contentholder" id="droplist">
            <div class="list" draggable="true" data-type="inputText">
                <span class="text">Text</span>
            </div>

            <div class="list" draggable="true" data-type="email">
                <span class="text">Email</span>
            </div>

            <div class="list" draggable="true" data-type="textarea">
                <span class="text">Textarea</span>
            </div>

            <div class="list" draggable="true" data-type="select">
                <span class="text">Select</span>
            </div>

            <div class="list" draggable="true" data-type="radio">
                <span class="text">Radio</span>
            </div>

            <div class="list" draggable="true" data-type="checkbox">
                <span class="text">Checkbox</span>
            </div>

            <div class="list" draggable="true" data-type="number">
                <span class="text">Number</span>
            </div>

            <div class="list" draggable="true" data-type="date">
                <span class="text">Date</span>
            </div>

            <div class="list" draggable="true" data-type="phone">
                <span class="text">Tel</span>
            </div>

            <div class="list" draggable="true" data-type="url">
                <span class="text">URL</span>
            </div>
        </div>

        <div class="dropzone contentholder" id="dropzone"></div>
    </div>
</div>

<!-- Aura Modal -->
<div class="wp_contact_form_aura_modal">
    <div class="wp_contact_form_aura_modal_overlay"></div>

    <div class="wp_contact_form_aura_modal_box">
        <h3>Edit Field</h3>

        <div class="field-option">
            <label>
                <input type="checkbox" id="show_label"> Show Label
            </label>
            <input type="text" id="edit_label" placeholder="Enter label text">
        </div>

        <div class="field-option">
            <label>
                <input type="checkbox" id="show_placeholder"> Show Placeholder
            </label>
            <input type="text" id="edit_placeholder" placeholder="Enter placeholder text">
        </div>

        <div class="field-option">
            <label>
                <input type="checkbox" id="default_value"> Default Value
            </label>
            <input type="text" id="edit_default_value" placeholder="Enter default value">
        </div>

        <div id="option_editor" class="option-editor" style="display:none;">
            <h4>Options</h4>
            <div id="option_list"></div>
            <button type="button" id="add_new_option" class="button-primary">
                + Add Option
            </button>
        </div>

        <div class="modal-actions">
            <button class="button-primary" id="save_field_changes">Save</button>
            <button class="button" id="close_modal">Cancel</button>
        </div>
    </div>
</div>