// ==========================================================>> Core Library
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';

// ==========================================================>> Custom Library
import { environment as env } from 'environments/environment';

@Injectable({
    providedIn: 'root',
})
export class ProductsService {

    public url: string = env.apiUrl;
    public httpOptions = {
        headers: new HttpHeaders().set('Content-Type', 'application/json'),
    };

    constructor(private http: HttpClient) { }

    /**
     |-------------------------------------------------------------------
     | Learn Create Read Update Delete (CRUD)
     |-------------------------------------------------------------------
     |
     | develop by: Yim Klok
     |
     */
    // ==================== Create One Product
    create(data: object = {}): any {
        return this.http.post(this.url + '/products', data, this.httpOptions);
    }
    // ==================== Read All Products
    read(params = {}): any {
        const httpOptions = {
            headers: new HttpHeaders().set('Content-Type', 'application/json')
        };
        httpOptions['params'] = params;
        return this.http.get(this.url + '/products', httpOptions);
    }
    // =================== Update One Product
    update(id: number = 0, data: object = {}): any {
        return this.http.post(this.url + '/products/' + id, data, this.httpOptions);
    }
    // ==================== Delete One Product
    delete(id: number = 0): any {
        return this.http.delete(this.url + '/products/' + id, this.httpOptions);
    }
    //==================================================================
}
