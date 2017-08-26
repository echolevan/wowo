export function my_dialog(msg) {
    console.log(1)
    let html = `  <div class="dialog">
                <div class="dialog__overlay"></div>
                <div class="dialog__content  animated fadeIn"  style="border-radius: 5px">
                    <h2>${msg}</h2>
                    <div>
                        <button type="button" class="close_dialog" style="border-radius: 5px">确认</button>
                    </div>
                </div>
            </div>`

    $('#app').append(html);
    $(".dialog").addClass('dialog--open');
    $(document).on("click",".close_dialog , .close_other_dialog",function () {
        $('.dialog').remove();
    })
}
