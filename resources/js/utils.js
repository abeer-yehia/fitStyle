import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

export const deleteItem = (id, routeName, event) => {
  event.preventDefault();

  swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!',
  }).then((result) => {
    if (result.isConfirmed) {
        Inertia.delete(route(routeName, id));
    }
  });
};
