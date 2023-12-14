// ==========================================================>> Core Library
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

// ==========================================================>> Custom Library
import { productTypeModule } from 'app/main/product/type/product-type.module';
import { ProductsModule } from 'app/main/product/product/product.module';

const productRoutes: Routes = [
    {
        path: '',
        children: [
            {
                path: 'all',
                loadChildren: () => import('app/main/product/product/product.module').then(m => m.ProductsModule)
            },
            {
                path: 'product-types',
                loadChildren: () => import('app/main/product/type/product-type.module').then(m => m.productTypeModule)
            }
        ]
    },
];

@NgModule({
    imports: [
        RouterModule.forChild(productRoutes),
        ProductsModule,
        productTypeModule
    ],
    exports: [
        ProductsModule,
        productTypeModule
    ]
})
export class ProductModule{}
