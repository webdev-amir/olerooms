<div class="notificatonsideMenu offcanvas offcanvas-end" tabindex="-1" id="notificationRightMenu" aria-labelledby="offcanvasRightLabel">
   <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
   <div class="offcanvas-header aos-animate p-0 mB20">
      <h2 class="sec-title fz40 extraBold nunito black" id="offcanvasRightLabel"><span>Notifications</span></h2>
      <a href="{{route('dashboard.notifications')}}" class="blue fz16 bold text-decoration-none d-flex">
         View All
         <svg class="mL5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <path fill="none" d="M0 0h24v24H0z" />
            <path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z" fill="rgba(49,101,174,1)" />
         </svg>
      </a>
   </div>
   <div class="offcanvas-body scrollbarDesign p-0">
      <ul class="p-0 noti_list">
         <sidebar-notification-list-component></sidebar-notification-list-component>
      </ul>
   </div>
</div>