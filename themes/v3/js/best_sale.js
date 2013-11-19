$( document ).ready( function () {
    
    var ixLanguage = (function () {

        var _$cont = $( 'div#lang' ),
            _$btn = _$cont.find( 'p > a' ),
            _$target = _$cont.find( 'ul' ),
            _$list = _$target.find( 'li' );

        var _linkParent,
            _link,
            _open = false;

        /////////////////////////////////////////////////////////////////////////////////

        _$cont.css( 'width', '86px' );

        _$btn.bind( 'click', function () {
            if ( _open ) {
                _$btn.parent().eq(0).hide();
                _$btn.parent().eq(1).show();
                _$target.animate({ 'right' : '-675px' }, { complete : function () { _$cont.css( 'width', '86px' ); } });
                _open = false;
            } else {
                _$cont.css( 'width', '842px' );
                _$btn.parent().eq(1).hide();
                _$btn.parent().eq(0).show();
                _$target.animate({ 'right' : '83px' });
                _open = true;
            }
        });

        _$list.each( function ( idx, list ) {
            $(list).bind( 'click', function () {
                var _idx = _$list.length - 1 - idx;

                $(list).find( 'a' ).attr( 'href', $(_link).eq( _idx ).attr( 'link' ) );
            });
        });
        
        
    }());

    //========================================================================================//

});