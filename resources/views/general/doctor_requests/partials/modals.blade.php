<!-- Approve Modal -->
<div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <form method="POST" action="{{ route(get_area_name().'.doctor-requests.approve', $request->id) }}">
               @csrf
               @method('PUT')
               <div class="modal-header">
                   <h5 class="modal-title">موافقة الطلب</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                   <div class="mb-3">
                       <label for="notes" class="form-label">الملاحظات (اختياري)</label>
                       <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                   <button type="submit" class="btn btn-success">موافقة</button>
               </div>
           </form>
       </div>
   </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <form method="POST" action="{{ route(get_area_name().'.doctor-requests.reject', $request->id) }}">
               @csrf
               @method('PUT')
               <div class="modal-header">
                   <h5 class="modal-title">رفض الطلب</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                   <div class="mb-3">
                       <label for="reason" class="form-label">السبب <span class="text-danger">*</span></label>
                       <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
                   </div>
                   <div class="mb-3">
                       <label for="notes" class="form-label">الملاحظات (اختياري)</label>
                       <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                   <button type="submit" class="btn btn-danger">رفض</button>
               </div>
           </form>
       </div>
   </div>
</div>

<!-- Done Modal -->
<div class="modal fade" id="doneModal{{ $request->id }}" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <form method="POST" action="{{ route(get_area_name().'.doctor-requests.done', $request->id) }}">
               @csrf
               @method('PUT')
               <div class="modal-header">
                   <h5 class="modal-title">إكمال الطلب</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                   <div class="mb-3">
                       <label for="notes" class="form-label">الملاحظات (اختياري)</label>
                       <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                   <button type="submit" class="btn btn-info">إكمال</button>
               </div>
           </form>
       </div>
   </div>
</div>
