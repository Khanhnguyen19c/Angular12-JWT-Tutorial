
import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl, Validators,AbstractControl} from '@angular/forms';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { TokenService } from 'src/app/Sevices/token.service';
import { JarwisService } from '../../Sevices/jarwis.service';

import { NotificationService } from '../../notification.service';
import { RegisterServiceService } from 'src/app/Services/register-service.service';

import { fileExtensionValidator } from '../../Validators/validator';

import { fileSizeValidator } from '../../Validators/valitatorfile';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})

export class RegisterComponent implements OnInit {

  myFiles:string [] = [];
  public error = [];
  acceptedExtensions = "jpg, jpeg, bmp, png, wav, mp3, mp4";

  myForm = new FormGroup({
    images: new FormControl('', [Validators.required]),
    fname: new FormControl('', [Validators.required]),
    lname: new FormControl('', [Validators.required]),
    email: new FormControl('',
    [ Validators.required,
        Validators.minLength(6),
        Validators.pattern("[^ @]*@[^ @]*"),
        validatorEmail
    ]),
    password: new FormControl('', [Validators.required, Validators.minLength(6)]),
    password_confirmation: new FormControl('', [Validators.required]),
    phone: new FormControl('', [Validators.required]),
    shopname: new FormControl('', [Validators.required]),
    address: new FormControl('', [Validators.required]),
    hotline: new FormControl('', [Validators.required]),
    taxcode: new FormControl('', [Validators.required]),
    file: new FormControl('', [
      Validators.required,
      fileSizeValidator,
      fileExtensionValidator(this.acceptedExtensions), //check extension

    ]),
    fileSource: new FormControl('', [Validators.required])
  });


  constructor(
    private toastr: ToastrService,
    private Jarwis: JarwisService,
    private Token:TokenService,
    private router:Router,
    private notifyService : NotificationService,
    private RegisterService: RegisterServiceService
    ) { }

  get f(){
    return this.myForm.controls;
  }

  onFileChange(event:any) {

    for (var i = 0; i < event.target.files.length; i++) {
      this.myFiles.push(event.target.files[i]);
      }
    // if (event.target.files.length > 0) {
    //   const file = event.target.files[0];
    //   this.myForm.patchValue({
    //     images: file
    //   });
    // }
  }
  ngOnInit(): void {
    }

  onSubmit(){

    const formData = new FormData();
    for (var i = 0; i < this.myFiles.length; i++) {
      formData.append("images[]", this.myFiles[i]);
    }
     formData.append('fname', this.myForm.get('fname')!.value);
     formData.append('lname', this.myForm.get('lname')!.value);
     formData.append('email', this.myForm.get('email')!.value);
     formData.append('password', this.myForm.get('password')!.value);
     formData.append('password_confirmation', this.myForm.get('password_confirmation')!.value);
     formData.append('phone', this.myForm.get('phone')!.value);
     formData.append('shopname', this.myForm.get('shopname')!.value);
     formData.append('hotline', this.myForm.get('hotline')!.value);
     formData.append('address', this.myForm.get('address')!.value);
     formData.append('taxcode', this.myForm.get('taxcode')!.value);
    // formData.append('file', this.myForm.get('images')!.value);
    // console.log(formData);

    //call API
   this.Jarwis.register(formData).subscribe(
    data => this.handleResponse(data),
    error => this.handleError(error)
   );
    // console.log('test');
  }
  //return Success
  handleResponse(data:any){
    this.notifyService.showSuccess("Register Account successfully.Please check your email for confirmation!!", "Notification"),
    this.Token.handle(data.access_token); // get token
    this.router.navigateByUrl('/thanks'); // return Profile
  }
  //return validate error
  handleError(error:any){
    this.notifyService.showError("Register Account Error!!", "Notification")
    this.error = error.error.errors;
  }

}
//validate email
function validatorEmail(control: FormControl) {
  let email = control.value; //get value email
  if (email && email.indexOf("@") != -1){
    let [main, domain] = email.split("@"); //get value after @
    if (domain !== "gmail.com") {
      return {
        emailDomain: {
          // data : console.log(main.length),
          parsedDomain: domain
        }
      }
  }
  }
  return null;
}

