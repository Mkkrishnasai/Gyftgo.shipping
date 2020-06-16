@extends('layouts.dashboard')

@section('styles')
<style>
    .row {
        margin-top: 20px;
    }
</style>
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="Form" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="row">
                    <div class="col-lg-6">
                        <input type="text" name="username" value="{{ isset($customer) && $customer->username ? $customer->username : old('username') }}" class="form-control ShouldNotEmpty @error('username') is-invalid @enderror" placeholder="Username">
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="email" value="{{ isset($customer) && $customer->email ? $customer->email : old('email') }}" data-pattern="^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$" class="form-control ShouldNotEmpty ShouldMatchPattern @error('email') is-invalid @enderror" placeholder="Email" autocomplete="off">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <input type="password" value="{{ old('password') }}"  name="password" class="form-control {{ isset($customer) ? '' : 'ShouldNotEmpty' }} @error('password') is-invalid @enderror" placeholder="{{ isset($customer) ? 'Password here ( Leave it Empty if dont want to update !)' : 'password here' }}" autocomplete="off">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="full_name" value="{{ isset($customer) && $customer->full_name ? $customer->full_name : old('full_name') }}" class="form-control ShouldNotEmpty @error('full_name') is-invalid @enderror" placeholder="Full Name" autocomplete="off">
                        @error('full_name')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <input type="text" name="website" value="{{ isset($customer) && $customer->website ? $customer->website: old('website') }}" class="form-control ShouldNotEmpty @error('website') is-invalid @enderror" placeholder="Website" autocomplete="off">
                        @error('website')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="mobile_number" value="{{ isset($customer) && $customer->mobile_number ? $customer->mobile_number : old('mobile_number') }}" class="form-control ShouldNotEmpty @error('mobile_number') is-invalid @enderror" placeholder="Mobile Number" autocomplete="off">
                        @error('mobile_number')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <input type="text" name="address1" value="{{ isset($customer) && $customer->address1 ? $customer->address1 : old('address1') }}" class="form-control ShouldNotEmpty @error('address1') is-invalid @enderror" placeholder="Address 1" autocomplete="off">
                        @error('address1')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="address2" value="{{ isset($customer) && $customer->address2 ? $customer->address2 : old('address2') }}" class="form-control ShouldNotEmpty @error('address2') is-invalid @enderror" placeholder="Address 2" autocomplete="off">
                        @error('address2')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <input type="text" name="company_name" value="{{ isset($customer) && $customer->company_name ? $customer->company_name : old('company_name') }}" class="form-control ShouldNotEmpty @error('company_name') is-invalid @enderror" placeholder="Company Name" autocomplete="off">
                        @error('company_name')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="city" value="{{ isset($customer) && $customer->city ? $customer->city : old('city') }}" class="form-control ShouldNotEmpty @error('city') is-invalid @enderror" placeholder="city" autocomplete="off">
                        @error('city')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <input type="text" name="state" value="{{ isset($customer) && $customer->state ? $customer->state : old('state') }}" class="form-control ShouldNotEmpty @error('state') is-invalid @enderror" placeholder="State" autocomplete="off">
                        @error('state')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="country" value="{{ isset($customer) && $customer->country ? $customer->country : old('country') }}" class="form-control ShouldNotEmpty @error('country') is-invalid @enderror" placeholder="Country" autocomplete="off">
                        @error('country')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <input type="text" name="zip_code" value="{{ isset($customer) && $customer->zip_code ? $customer->zip_code : old('zip_code') }}" class="form-control ShouldNotEmpty @error('zip_code') is-invalid @enderror" placeholder="Zip Code" autocomplete="off">
                        @error('zip_code')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="fax" value="{{ isset($customer) && $customer->fax ? $customer->fax : old('fax') }}" class="form-control ShouldNotEmpty @error('fax') is-invalid @enderror" placeholder="Fax" autocomplete="off">
                        @error('fax')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="text-center" style="margin: 10px;" id="message">

                </div>
                <div class="text-center">
                    <button type="submit" name="submit" id="btnSubmit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let validateShouldNotEmpty = () => {
        $(".validation-message").remove();
        let valid = true;
        $('.ShouldNotEmpty').each((ind, val) => {
            if ($(val).attr('name') && ( !$(val).val() || $(val).val() === '')) {
                valid = false;
                $(val).parent().append('<p class="validation-message" style="color: red;font-size: .9rem; margin-top: 5px;">Please fill in this field.</p>');
                $(val).focus();
                $(".collapse").removeClass('show');
                $(val).parent().parents('.collapse').addClass('show');
                return false;
            }
        });
        return valid;
    };

    let validateShouldMatchPattern = () => {
        let valid = true;
        $('.ShouldMatchPattern').each((ind, val) => {
            if ($(val).attr('data-pattern')) {
                let regExp = new RegExp($(val).data('pattern'));
                let isValid = regExp.test($(val).val().trim())
                if (!isValid) {
                    valid = false;
                    $(val).parent().append('<p class="validation-message" style="color: red;font-size: .9rem; margin-top: 5px;">Please enter a valid format.</p>');
                    $(val).focus();
                    $(".collapse").removeClass('show');
                    $(val).parent().parents('.collapse').addClass('show');
                    return false;
                }
            }
        });
        return valid;
    };
$(document).on('submit','#Form', function (e) {
    e.preventDefault();
    var fd = $(this).serialize();

    if(true)
    {
        $.ajax({
            method : 'POST',
            url : '{{ isset($customer) ? route('customer.update', $customer->id) : route('customer.store') }}',
            cache : false,
            processData : false,
            data : fd,
            dataType : 'json',
            success : function (data) {
                if(data.status == 200){
                    window.location = '{{ route('customer.index') }}';
                }else if(data.status == 2){
                    $('.validation-message').remove();
                    $.each(data.errors, function (ind, error) {
                       $('input[name='+ind+']').attr('class' , 'form-control is-invalid');
                       $('input[name='+ind+']').parent().append('<p class="validation-message" style="color: red;font-size: .9rem; margin-top: 5px; ">'+error[0]+'</p>');
                       $('input[name='+ind+']').focus();
                    });
                }
            }
        });
    }
});
</script>
@endsection
