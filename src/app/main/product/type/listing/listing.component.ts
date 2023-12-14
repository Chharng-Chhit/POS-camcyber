// ==========================================================>> Core Library
import { Component, OnInit } from '@angular/core';

// ==========================================================>> Third Party Library
import { MatDialog, MatDialogConfig } from '@angular/material/dialog';
import { MatTableDataSource } from '@angular/material/table';

// ==========================================================>> Custom Library
import { ConfirmDialogComponent } from 'app/shared/confirm-dialog/confirm-dialog.component';
import { SnackbarService } from 'app/shared/services/snackbar.service';
import { LoadingService } from 'helpers/services/loading';
import { CreateComponent } from '../create/create.component';
import { ProductTypeService } from '../product-type.service';
import { UpdateComponent } from '../update/update.component';

@Component({
  selector: 'app-listing',
  templateUrl: './listing.component.html',
  styleUrls: ['./listing.component.scss']
})
export class ListingComponent implements OnInit {

  public displayedColumns: string[] = ['no', 'name', 'n_of_products', 'date', 'action'];
  public dataSource: any;
  public isLoading: boolean = true;
  public data: any = [];

  constructor(
    private _productTypeService: ProductTypeService,
    private _snackBarService: SnackbarService,
    private _loadingService: LoadingService,
    private _dialog: MatDialog
  ) { }

  ngOnInit(): void {
    this.isLoading = true;
    this._loadingService.show();
    this._productTypeService.read().subscribe((res: any) => {
      this._loadingService.hide();
      this.isLoading = false;
      this.data = res;
      this.dataSource = new MatTableDataSource(this.data);
    }, (err: any) => {
      this._loadingService.hide();
      this.isLoading = false;
      console.log(err);
      this._snackBarService.openSnackBar(err.error.message, 'error');
    })
  }

  create(): void {
    const dialogConfig = new MatDialogConfig();
    dialogConfig.width = "550px";
    const dialogRef = this._dialog.open(CreateComponent, dialogConfig);
    dialogRef.componentInstance.CreateProjectType.subscribe((response: any) => {
      this.data.push(response);
      this.data.sort((a: any, b: any) => a.name.localeCompare(b.name));
      this.dataSource = new MatTableDataSource(this.data);
    });
  }
  update(row: any): void {
    const dialogConfig = new MatDialogConfig();
    dialogConfig.data = row;
    dialogConfig.width = "550px";
    const dialogRef = this._dialog.open(UpdateComponent, dialogConfig);
    dialogRef.componentInstance.UpdateProjectType.subscribe((response: any) => {
      let copy: any[] = [];
      this.data.forEach((v: any) => {
        if (v.id == response.id) {
          copy.push(response);
        } else {
          copy.push(v);
        }
      });
      this.data = copy;
      this.data.sort((a: any, b: any) => a.name.localeCompare(b.name));
      this.dataSource = new MatTableDataSource(this.data);
    });
  }
  delete(id: number = 0): void {
    const dialogConfig = new MatDialogConfig();
    dialogConfig.width = "320px";
    const dialogRef = this._dialog.open(ConfirmDialogComponent, dialogConfig);
    dialogRef.afterClosed().subscribe((result) => {
      if (result) {
        this._productTypeService.delete(id).subscribe((res: any) => {
          this._snackBarService.openSnackBar(res.message, '');
          let copy: any[] = [];
          this.data.forEach((obj: any) => {
            if (obj.id !== id) {
              copy.push(obj);
            }
          });
          this.data = copy;
          this.data.sort((a: any, b: any) => a.name.localeCompare(b.name));
          this.dataSource = new MatTableDataSource(this.data);
        }, (err: any) => {
          console.log(err);
          this._snackBarService.openSnackBar('Something went wrong.', 'error');
        });
      }
    });
  }
}
