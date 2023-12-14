
// ==========================================================>> Core Library
import { Component, EventEmitter, Inject, OnInit, ViewChild } from '@angular/core';
import { NgForm, UntypedFormBuilder, UntypedFormGroup, Validators } from '@angular/forms';

// ==========================================================>> Third Party Library
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';

// ==========================================================>> Custom Library
import { SnackbarService } from 'app/shared/services/snackbar.service';
import { ProductTypeService } from '../product-type.service';
@Component({
  selector: 'app-update',
  templateUrl: './update.component.html',
  styleUrls: ['./update.component.scss']
})
export class UpdateComponent implements OnInit {

  @ViewChild('updateNgForm') updateNgForm: NgForm;
  UpdateProjectType = new EventEmitter();
  public saving: boolean = false;
  public update: UntypedFormGroup;
  constructor(
    @Inject(MAT_DIALOG_DATA) public getRow: any,
    private dialogRef: MatDialogRef<UpdateComponent>,
    private _formBuilder: UntypedFormBuilder,
    private _productTypeService: ProductTypeService,
    private snackBar: SnackbarService
  ) {
    dialogRef.disableClose = true;
  }

  ngOnInit(): void {
    this.formBuilder();
  }

  formBuilder(): void {
    this.update = this._formBuilder.group({
      name: [this.getRow.name, Validators.required],
  });
  }

  submit(): void {
    // Return if the form is invalid
    if (this.update.invalid) {
      return;
    }

    // Disable the form
    this.update.disable();

    // Saving
    this.saving = true;

    // call to api
    this._productTypeService.update(this.getRow.id,this.update.value).subscribe(
      (res: any) => {
        this.dialogRef.close();
        let row: object = {
          id: res.product_type.id,
          name: res.product_type.name,
          n_of_products: 0
        }
        this.UpdateProjectType.emit(row);
        //use snack bar to opron message
        this.snackBar.openSnackBar(res.message, '');
      },
      (err: any) => {

        // Re-enable the form
        this.update.enable();

        // saved
        this.saving = false;

        let errors: any[] = [];
        errors = err.error.errors;
        let messages: any[] = [];
        let text: string = '';
        if (errors.length > 0) {
          errors.forEach((v: any) => {
            messages.push(v.message)
          });
          if (messages.length > 1) {
            text = messages.join('-');
          } else {
            text = messages[0];
          }
        } else {
          text = err.error.message;
        }
        this.snackBar.openSnackBar(text, 'error');
      }
    );
  }

}
