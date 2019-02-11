// import Cropper from 'cropperjs';
import Dropzone from 'dropzone';
// require('dropzone/dist/min/dropzone.min.css');
// import 'dropzone/dist/min/dropzone.min.css';

require('../../css/Product/edit.scss');

var sizeCount = '{{ form.sizes | length }}';

function addSizeForm() {
    var sizeListPrototype = '<tr><td>{{ form_label(form.sizes) }}</td><td>';
    sizeListPrototype += '{{ form_widget(form.sizes.vars.prototype) }}';
    sizeListPrototype += '</td></tr><tr style="border-bottom: 1px solid black;"><td>{{ form_label(form.availabilities) }}</td><td>';
    sizeListPrototype += '{{ form_widget(form.availabilities.vars.prototype) }}';
    sizeListPrototype += '</td></tr>';

    sizeListPrototype = sizeListPrototype.replace(/__name__/g, sizeCount);
    sizeCount++;

    $('.size-list').append(sizeListPrototype);
}

$(function() {
    $('#add-size').click(function(e) {
        e.preventDefault();

        addSizeForm();
    });
});

// let cropper;
// var preview = $('#product-image')[0];
// var image_input = $('#product_images')[0];
//
// function previewFile() {
//     let file = image_input.files[0];
//     let reader = new FileReader();
//
//     reader.addEventListener('load', function (event) {
//         preview.src = reader.result;
//     }, false);
//
//     if (file) {
//         reader.readAsDataURL(file);
//     }
// }
//
// $(function() {
//     $('#product_images').on('change', function () {
//         previewFile();
//     });
//
//     preview.addEventListener('load', function (event) {
//         // cropper.reset();
//         if (cropper) {
//             cropper.destroy();
//         }
//         cropper = new Cropper(preview, {
//             aspectRatio: 1 / 1
//         })
//     })
// });



// Dropzone.autoDiscover = false;
// var imagesDropzone = new Dropzone(".dropzone", {
//     // url: '{{ path('fileuploadhandler') }}' ,
//     url: '/product/new', // TODO: change!!!
//     acceptedFiles: 'image/*',
//     maxFilesize: 10,  // in Mb
//     addRemoveLinks: true,
//     uploadMultiple: true,
//     autoProcessQueue: false,
//     parallelUploads: 100,
//     maxFiles: 100,
//     init: function () {
//         var myDropzone = this;
//         $('#product_save').on("click", function (e) {
//             // $(this).attr("disabled", "disabled");
//             console.log('saveclick');
//             // this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {
//             // Make sure that the form isn't actually being sent.
//             e.preventDefault();
//             e.stopPropagation();
//             myDropzone.processQueue();
//         });
//         this.on("maxfilesexceeded", function (file) {
//             this.removeFile(file);
//         });
//         this.on("sending", function (file, xhr, formData) {
//             console.log('onsending');
//             console.log(file);
//             console.log(xhr);
//
//             formData.append('id', 5);
//
//             // console.log(formData);
//             // send additional data with the file as POST data if needed.
//             // formData.append("key", "value");
//         });
//         this.on("success", function (file, response) {
//             console.log('onsuccess');
//             // console.log(response);
//             this.removeFile(file);
//             // if (response.uploaded)
//             //     alert('File Uploaded: ' + response.fileName);
//             // window.location.href = '/product/new';
//         });
//     }
// });

// // Create the mock file:
// var mockFile = { name: "Filename", size: 12345 };
//
// // Call the default addedfile event handler
// imagesDropzone.emit("addedfile", mockFile);
//
// // And optionally show the thumbnail of the file:
// imagesDropzone.emit("thumbnail", mockFile, "https://d3icht40s6fxmd.cloudfront.net/sites/default/files/test-product-test.png");
// // Or if the file on your server is not yet in the right
// // size, you can let Dropzone download and resize it
// // callback and crossOrigin are optional.
// imagesDropzone.createThumbnailFromUrl(file, imageUrl, callback, crossOrigin);
//
// // Make sure that there is no progress bar, etc...
// imagesDropzone.emit("complete", mockFile);
//
// // If you use the maxFiles option, make sure you adjust it to the
// // correct amount:
// var existingFileCount = 1; // The number of files already uploaded
// imagesDropzone.options.maxFiles = imagesDropzone.options.maxFiles - existingFileCount;