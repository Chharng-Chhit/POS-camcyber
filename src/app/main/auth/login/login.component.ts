// ==========================================================>> Core Library
import { Component, OnInit} from '@angular/core';
import { UntypedFormBuilder, UntypedFormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';

// =========================================================>> Custom Library
// Shared
import { SnackbarService } from 'app/shared/services/snackbar.service';

// ==========================================================>> Custom Library
import { AuthService } from 'app/core/auth/auth.service';
import { Animations } from 'helpers/animations';
import { MyProfileService } from 'app/main/my-profile/my-profile.service';

@Component({
    selector: 'app-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.scss'],
    animations   : Animations
})

export class LoginComponent implements OnInit {

    // ===>> Public Variables used in the component and template.
    public logInForm    : UntypedFormGroup;
    public isShowAlert  : boolean            = false;
    public isLoading    : boolean            = false;

    /**
     * Constructor
     */
    constructor(

        //===> Private Variables used in this component only
        private _authService: AuthService,
        private _myProfielService: MyProfileService,
        private _formBuilder: UntypedFormBuilder,
        private _router: Router,
        private _snackBar: SnackbarService // for Displaying Message
    )
    {

    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------

    /**
     * On init
     */
    ngOnInit(): void
    {
        // Build LogInForm the form
        this.logInForm = this._formBuilder.group({
            username    : ['0977779688', [Validators.required]],
            password    : ['123456', Validators.required]
        });
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Public methods
    // -----------------------------------------------------------------------------------------------------

    login(): void{

        // Start displaying Spinner in Button
        this.isLoading = true;

        // Call API for Login
        this._authService.login(this.logInForm.value).subscribe(
        
            // ======================================>> Success 200
            (res: any) => {
                
                // Save User Data to Local Storage.
                if(res.user){
                    
                    let user = {
                        'id'        : res.user.id,
                        'name'      : res.user.name,
                        'avatar'    : res.user.avatar, 
                        'phone'     : res.user.phone,
                        'email'     : res.user.email
                    }
                    //this._myProfielService.user = user;
                    localStorage.setItem('user',JSON.stringify(user));
                    localStorage.setItem('role', res.role);
                }

                // Navigate to the dashboard
                this._router.navigateByUrl('/dashboard');

                
            },

            // ======================================>> Not Suceess 
            (err: any) => {

                // Hide Spinner in Button
                this.isLoading = false;

                // Display Error Message
                this._snackBar.openSnackBar('Username or Password is not correct.', 'error');

            }
        );
    }
}
