import type Swal from 'sweetalert2'

declare module '#app' {
  interface NuxtApp {
    $swal: typeof Swal
  }
}

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $swal: typeof Swal
  }
}
