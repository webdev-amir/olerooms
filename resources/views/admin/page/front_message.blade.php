{!! Html::script('lobibox/lib/jquery.1.11.min.js') !!} 
{!! Html::script('lobibox/js/lobibox.js') !!}
@if(isset($errors))
    @if ($errors->any())
        <script>
         $(function () {
                (function () {
                    Lobibox.notify('error', {
                        rounded: false,
                        delay: 5000,
                        delayIndicator: true,
                        position: "top right",
                        msg: "<?php foreach($errors->all() as $error){ echo $error."<br>"; } ?>"
                    });
                })();
            });
        </script>
    @endif
@endif
    @if(session()->has('success') or session()->has('warning') or session()->has('info') or session()->has('danger') or session()->has('error'))
        @foreach (['danger', 'warning', 'success', 'info','error'] as $msg)
            @if(session()->has($msg))
                <script>
                var msgType = "{{$msg}}"
                 $(function () {
                        (function () {
                            Lobibox.notify(msgType, {
                                rounded: false,
                                delay: 10000,
                                delayIndicator: true,
                                 position: "top right",
                                msg: "{{ session()->get($msg)}}"
                            });
                        })();
                    });
                </script>
                <?php  session()->forget($msg); ?>
            @endif
        @endforeach
    @endif
@if (session('status'))
<script>
    var msgType = "success"
     $(function () {
            (function () {
                Lobibox.notify(msgType, {
                    rounded: false,
                    delay: 4000,
                    delayIndicator: true,
                     position: "top right",
                    msg: "{{ session('status') }}"
                });
            })();
        });
</script>
@endif
<style type="text/css">
.lobibox-notify-msg{max-height:100px !important;}
</style>