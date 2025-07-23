import { ComponentFixture, TestBed } from '@angular/core/testing';

import { Commoners } from './commoners';

describe('Commoners', () => {
  let component: Commoners;
  let fixture: ComponentFixture<Commoners>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [Commoners]
    })
    .compileComponents();

    fixture = TestBed.createComponent(Commoners);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
