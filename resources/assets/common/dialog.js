export function my_dialog(msg , button_class = '') {
    console.log(button_class)
    let html = `  <div class="dialog">
                <div class="dialog__overlay"></div>
                <div class="dialog__content  animated fadeIn"  style="border-radius: 2px">
                    <h2>${msg}</h2>
                    <div>
                        <button type="button" class="close_dialog ivu-btn-primary ${button_class}" style="border-radius: 2px">确认</button>
                    </div>
                </div>
            </div>`

    $('#app').append(html);
    $(".dialog").addClass('dialog--open');
    $(document).on("click",".close_dialog , .close_other_dialog",function () {
        $('.dialog').remove();
    })
}
