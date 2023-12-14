// ==========================================================>> Core Library
import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';

// ==========================================================>> Custom Library
import { environment as env } from 'environments/environment';

@Component({
  selector: 'app-product-item',
  templateUrl: './product-item.component.html',
  styleUrls: ['./product-item.component.scss']
})
export class ProductItemComponent implements OnInit {

  @Input() data: any = null; //Get Data from Parent
  @Output() result = new EventEmitter;  //Send data to Parent
  public fileUrl: string = env.fileUrl;
  constructor() { }

  ngOnInit(): void {
  }

  onOutput() {
    console.log(this.data);
    this.result.emit(this.data);
  }

}
