import { TestBed } from '@angular/core/testing';

import { Forms } from './forms';

describe('Forms', () => {
  let service: Forms;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(Forms);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
