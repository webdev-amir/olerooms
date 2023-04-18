<div class="modal fade login" id="login" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content relative">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Log In')}}</h4>
                <span class="c-pointer" data-dismiss="modal" aria-label="Close">
                    <i class="input-icon field-icon fa">
                        <img src="{{url('images/ico_close.svg')}}" alt="close">
                    </i>
                </span>
            </div>
            <div class="modal-body relative">
                <form class="bravo-form-login" method="POST" action="{{ route('customer.login') }}">
                    <input type="hidden" name="redirect" value="{{url()->full()}}" id="redirect">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" name="email" autocomplete="off" placeholder="{{__('Email address')}}">
                        <i class="input-icon icofont-mail"></i>
                        <span class="invalid-feedback error error-email"></span>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" autocomplete="off"  placeholder="{{__('Password')}}">
                        <i class="input-icon icofont-ui-password"></i>
                        <span class="invalid-feedback error error-password"></span>
                    </div>
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="remember-me" class="mb0">
                                <input type="checkbox" name="remember" id="remember-me" value="1"> {{__('Remember me')}} <span class="checkmark fcheckbox"></span>
                            </label>
                            <a href="{{ route('customer.password.request') }}" target="blank">{{__('Forgot Password?')}}</a>
                        </div>
                    </div>
                    <div class="error message-error invalid-feedback"></div>
                    <div class="form-group">
                        <button class="btn btn-primary form-submit" type="submit">
                            {{ __('Login') }}
                            <span class="spinner-grow spinner-grow-sm icon-loading" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                                         <!--
                    <div class="c-grey font-medium f14 text-center"> {{__('Do not have an account?')}} 
                        <a href="" data-target="#register" data-toggle="modal">{{__('Sign Up')}}</a> 
                    </div>-->
                </form>

            </div>
        </div>
    </div>
</div>