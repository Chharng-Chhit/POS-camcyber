// ==========================================================>> Core Library
import { enableProdMode } from '@angular/core';

// ==========================================================>> Third Party Library
import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';

// ==========================================================>> Custom Library
import { environment } from 'environments/environment';
import { AppModule } from 'app/app.module';
import 'hammerjs';

if ( environment.production )
{
    enableProdMode();
}

platformBrowserDynamic().bootstrapModule(AppModule)
                        .catch(err => console.error(err));
