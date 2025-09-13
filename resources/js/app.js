import './bootstrap';
import Alpine from 'alpinejs';
import $ from 'jquery';
import 'select2';
// import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

window.$ = window.jQuery = $;
window.Alpine = Alpine;

Alpine.start();

// document.addEventListener('DOMContentLoaded', () => {
//     const el = document.querySelector('#body');
//     if (el) {
//         ClassicEditor.create(el, {
//             ckfinder: {
//                 // arahkan ke LFM upload
//                 uploadUrl:
//                     '/laravel-filemanager/upload?type=Images&_token=' +
//                     document.querySelector('meta[name="csrf-token"]').content,
//             },
//         }).catch(console.error);
//     }
// });
